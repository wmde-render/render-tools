#!/opt/ts/python/2.7/bin/python
#$ -l h_rt=10:00:00
#$ -j y
#$ -m ae
#$ -m bae

"""ChangeDetector2

  Author: Philipp Zedler
  Copyright (c) 2012, Wikimedia Deutschland
  All rights reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:
      * Redistributions of source code must retain the above copyright
        notice, this list of conditions and the following disclaimer.
      * Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution.
      * Neither the name of Wikimedia Deutschland nor the
        names of its contributors may be used to endorse or promote products
        derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY WIKIMEDIA DEUTSCHLAND ''AS IS'' AND ANY
  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL WIKIMEDIA DEUTSCHLAND BE LIABLE FOR ANY
  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

== Disclaimer ==

  NOTE: This software is not released as a product. It was written primarily for
  Wikimedia Deutschland's own use, and is made public as is, in the hope it may
  be useful. Wikimedia Deutschland may at any time discontinue developing or
  supporting this software. There is no guarantee any new versions or even fixes
  for security issues will be released.

This program tries to detect changes in the topics of Wikipedia articles
by analyzing metadata about the articles which is stored on the
toolserver. Given a day it looks for articles that had extraordinarily
huge editing activity at this day. Please read the file
doc/ChangeDetector2.rst to learn more about how this program works.

When called from the command line, this program takes the day in the
format <yyyymmdd> as an argument.

Requirements:

  - python

  - python's oursql module

  - the file ~/.my.cnf containing data necessery to establish
    a mysql connection.

  - the table toolserver.sql containing a list of the servers of
    the wikipedia metadata in each language

  - this program requires the current design of the sql tables
    page, langlinks and revision which exist for all language versions.


"""


import os
import sys
import time
import cmd
import copy
import traceback
import ConfigParser
import xml.dom.minidom
import itertools
import oursql
import csv

from datetime import date, timedelta

#Settings = None
#SQL_Cursors = None

# forgetting to change this after copying the script leads to annoying errors. why don't we use sys.path[0]? 
#       http://docs.python.org/library/sys.html#sys.path
PATH_TO_THIS_FILE = sys.path[0] + '/'
#PATH_TO_THIS_FILE = '/home/jkroll/ChangeDetector/'
#PATH_TO_THIS_FILE = '/home/project/r/e/n/render/Programme/ChangeDetector/'
#PATH_TO_THIS_FILE = '/home/knissen/ChangeDetector/'
#PATH_TO_THIS_FILE = '/home/philipp/Projekte/11-10-03-UpToDatenessCheck/change/'
#PATH_TO_THIS_FILE = '/home/philipp/Projekte/11-10-UpToDatenessCheck/change/'

class MyObject(object):
    """Subclasses will have the function `_explain' that displays during
    the execution what's happening. 
    
    """
    
    __DebugLevel = 1
    
    def get_debug_level(self):
        return MyObject.__DebugLevel
    
    def set_debug_level(self, level):
        MyObject.__DebugLevel = level
    
    def _explain(self, debug_level, message):
        if int(MyObject.__DebugLevel) >= int(debug_level) \
              and int(debug_level) > 0:
            print message
            sys.stdout.flush()

class MyOursqlException(Exception):
    def __init__(self, message):
        self.__message = message
    def __str__(self):
        return self.__message


class DatabaseInterface(MyObject):
    """Manage database access.
    
    Sorry. This class must be placed at the beginning of the file,
    because a few important classes extend ist. In order to read
    more important code, please continue with the class CountFetcher."""
    
    table_layout = {}  # Change this for subclasses!
    index_layout = {}  # Change this for subclasses!
    
    def __init__(self):
        for table_name in self.table_layout.keys():
            if not self.__table_exists(table_name):
                self.__create_table(table_name)
                self.__create_index(table_name)
                #TODO: better: pass by copy
    
    #def __find_update_condition(self):
    #    """This must be implemented by subclasses."""
    #    raise NotImplementedError
    
    
    def __table_exists(self, table_name):
        sql_command = """
              SHOW TABLES"""
        SQL_Cursors()['auxiliary'].execute(sql_command)
        sql_result = SQL_Cursors()['auxiliary'].fetchall()
        # TODO: for row in SQL_Cursors()['auxiliary']: works not. Why?
        result = False
        for row in sql_result:
            if row[0] == table_name:
                result = True
        return result
    
    def __create_table(self, table_name):
        fields = []
        for name, type in self.table_layout[table_name]:
            fields.append(' '.join([name, type]))
        sql_statement = "CREATE TABLE %(table)s (\n%(fields)s)" % \
              {'table': table_name,
               'fields': ',\n'.join(fields)}
        SQL_Cursors()['auxiliary'].execute(sql_statement)
    
    def __create_index(self, table_name):
        unique = ''
        for index_name, index_fields \
              in self.index_layout[table_name].iteritems():
            if isinstance(index_name, (list, tuple)):
                if index_name[0] == 'UNIQUE':
                    unique = 'UNIQUE'
                    index_name = index_name[1]
            sql_statement = """
                  CREATE %(unique?)s INDEX %(index_name)s
                  ON %(table_name)s (%(fields)s)""" % \
                  {'index_name': index_name,
                   'table_name': table_name,
                   'fields': ', '.join(index_fields),
                   'unique?': unique}
            SQL_Cursors()['auxiliary'].execute(sql_statement)
            unique = ''

class EditCount(DatabaseInterface):
    """Updater for the sql table `edit_count'.
    
    For each language and day passed to the constructor, this class
    checks if the table `edit_count' has a corresponding entry.
    If not, the number of edits per day is fetched for each article
    using the database `revision' on the toolserver. For days with
    zero edits, nothing will be inserted into `edit_count'.
    """
    table_layout = {
          'edit_count': (
                ('language', 'VARBINARY(20) DEFAULT NULL'),
                ('day', 'VARBINARY(8) DEFAULT NULL'),
                ('page_id', 'INT(8) DEFAULT NULL'),
                ('edits', 'SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0'),
                ('difference', 'SMALLINT(5) DEFAULT NULL'))}
    index_layout = {
          'edit_count': {
                ('UNIQUE', 'day_language_page'):
                      ('day', 'language', 'page_id'),
                'day_language_difference':
                      ('day', 'language', 'difference'),
                'day_language_edits':
                      ('day', 'language', 'edits')}}
    
    def __init__(self, languages, needed_days):
        """
        @type languages: list of strings
        @type needed_days: list of strings
        """
        DatabaseInterface.__init__(self)
        self._explain(1, "Checking the table `edit_count'.")
        self.__languages = copy.copy(languages)  # Pass by value.
        self.__needed_days = copy.copy(needed_days)
        self.__missing_days = {}
        self.__update_edit_counts()
    
    def __update_edit_counts(self):
        self.__find_missing_days()
        for language in self.__languages:
            for day in self.__missing_days[language]:
                self._explain(
                      1,
                      "Updating day %s in language %s for table `edit_count'." %
                      (day, language))
                self.__read_edits(language, day)
                self.__write_edits(language, day)
        EditCountDifference()
    
    def __find_missing_days(self):
        #TODO: rename
        for language in self.__languages:
            self.__find_missing_days_in_language(language)
            self._explain(
                  1,
                  "Edit counts must be fetched for %d days in language %s." %
                  (len(self.__missing_days[language]), language))
    
    def __find_missing_days_in_language(self, language):
        string_of_days = "('%s')" % "','".join(str(day) for day in self.__needed_days)
        sql_statement = """
              SELECT DISTINCT day
              FROM edit_count
              WHERE language = '%(language)s'
              AND day IN %(days)s""" % \
              {'language': language,
               'days': string_of_days}
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        sql_result = [
              Day(item) for row in SQL_Cursors()['auxiliary'].fetchall()
              for item in row]
        self.__missing_days[language] = \
              set(self.__needed_days) - set(sql_result)
    
    def __read_edits(self, language, day):
        # Only COUNT(rev_page) > 1 makes it possible to perform the 
        # counting algorithms within a reasonable time.
        sql_statement = """
              SELECT /* SLOW_OK */
              rev_page, COUNT(rev_page)
              FROM revision
              WHERE rev_timestamp
              BETWEEN '%(day)s000000' AND '%(day)s999999'
              GROUP BY rev_page
              HAVING COUNT(rev_page) > 1
              """ % {'day':day}
        SQL_Cursors()[language].execute(sql_statement)
        self.__edit_numbers = \
              dict(SQL_Cursors()[language].fetchall())
    
    def __write_edits(self, language, day):
        parameters = []
        for page_id, counts in self.__edit_numbers.iteritems():
            parameters.append([language, str(day), page_id, counts])
        sql_statement = """
              INSERT INTO edit_count
              (language, day, page_id, edits)
              VALUES (?, ?, ?, ?)"""
        SQL_Cursors()['auxiliary'].executemany(sql_statement, parameters)

class NoticedArticle(DatabaseInterface):
    """This class updates the sql-table noticed_article by applying the
    algorithms defined in the class CountingAlgorithm for the day
    which is passed to the constructor.
    It provides the public method get_reference days such that other
    classed do not need to calculate the reference days on their own.
    """
    table_layout = {
          'noticed_article': (
                ('day', 'VARBINARY(8) NOT NULL'),
                ('identifier', 'INT(8)'),
                ('language', 'VARBINARY(20) NOT NULL'),
                ('page_id', 'INT(8) UNSIGNED'),
                ('detected_by_mf', 'BOOL'),
                ('detected_by_cta', 'BOOL'),
                ('detected_by_cts', 'BOOL'),
                ('detected_by_mdf', 'BOOL'))}
    index_layout = {
          'noticed_article': {
                'day_identifier':
                ('day', 'identifier')}}
    
    def __init__(self, day_to_check):
        DatabaseInterface.__init__(self)
        self._explain(
              1,
              "Checking the table `noticed_article' for day %s" %
              day_to_check)
        self.__day_to_check = Day(day_to_check)
        self.__find_reference_days()
        if not self.__edits_are_counted():
            self._explain(1, "Table `noticed_article' needs update.")
            self.__count_edits()
    
    def __count_edits(self):
        self.__update_edit_counts_table()
        self.__delete_old_table_entries()
        self.__find_changed_articles()
        self._explain(3, self.__noticed_articles)
        self.__write_articles_to_database()
    
    def get_reference_days(self):
        return self.__reference_days
    
    def __edits_are_counted(self):
        sql_command = """
              SELECT DISTINCT day FROM noticed_article
              WHERE day = %(day)s""" % \
                    {'day':self.__day_to_check}
        try:
            SQL_Cursors()['auxiliary'].execute(sql_command)
        except oursql.ProgrammingError:
            return False
        if SQL_Cursors()['auxiliary'].rowcount > 0:
            SQL_Cursors()['auxiliary'].fetchall() # tidy up
            return True
        else:
            return False
    
    def __delete_old_table_entries(self):
        sql_command = """
              DELETE FROM noticed_article WHERE day = %(day)s""" % \
              {'day':self.__day_to_check}
        SQL_Cursors()['auxiliary'].execute(sql_command)
    
    def __find_reference_days(self):
        reference_days = int(Settings()['number_of_reference_days'])
        self.__reference_days = [self.__day_to_check - 1]
        # We need yesterday to find the difference for today.
        day_object = Day(self.__day_to_check)
        for i in range(3, reference_days + 4):
            # 3 because we leave out the two first preceding days.
            # +4 because we need an additional day for the difference.
            self.__reference_days.append(self.__day_to_check - i)
    
    def __update_edit_counts_table(self):
        needed_days = set(self.__reference_days) | set([self.__day_to_check])
        edit_counts = EditCount(Settings()['languages'], needed_days)
        #edit_counts.get_edit_counts() #TODO: remove
    
    def __find_changed_articles(self):
        self.__noticed_articles = ArticleMerger()
        for language in Settings()['languages']:
            algorithm = CountingAlgorithm(language)
            algorithm.set_first_reference_day(self.__reference_days[-1])
            algorithm.set_last_reference_day(self.__reference_days[0])
            algorithm.set_day_to_check(self.__day_to_check)
            for article in algorithm:
                self.__noticed_articles.append(article)
    
    def __write_articles_to_database(self):
        self._explain(3, 'Write %s noticed articles.' % len(self.__noticed_articles))
        identifier = 0
        for article in self.__noticed_articles:
            for language in article.id.keys():
                self._explain(4, '      noticed articles in %s' % language)
                detected = {}
                for algorithm in ('mf', 'cta', 'cts', 'mdf'):
                    try:
                        detected[algorithm] = bool(
                              article.rating[algorithm][language])
                    except KeyError:
                        detected[algorithm] = False
                sql_command = """
                      INSERT INTO noticed_article
                      (day, identifier, language, page_id,
                       detected_by_mf, detected_by_cta,
                       detected_by_cts, detected_by_mdf)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)"""
                sql_parameters = (
                      str(self.__day_to_check),
                      identifier,
                      language,
                      article.id[language],
                      int(detected['mf']),
                      int(detected['cta']),
                      int(detected['cts']),
                      int(detected['mdf']))
                SQL_Cursors()['auxiliary'].execute(
                      sql_command, sql_parameters)
                #except Exception:
                  #pass
                #print "E"
            identifier = identifier + 1

class PageAndRevision(DatabaseInterface):
    """This class updates the auxiliary tables page and revision by
    fetching the rows of interest from the toolserver's tables page
    and revision which exist for each language version.
    """
    
    table_layout = {
          'page': (
                ('language', "varbinary(20) NOT NULL default''"),
                ('day', 'varbinary(8) NOT NULL'),
                ('identifier', 'int(8)'),
                ('detected_by_mf', 'tinyint(1) DEFAULT NULL'),
                ('detected_by_cta', 'tinyint(1) DEFAULT NULL'),
                ('detected_by_cts', 'tinyint(1) DEFAULT NULL'),
                ('detected_by_mdf', 'tinyint(1) DEFAULT NULL'),
                ('page_id', 'int(8) unsigned NOT NULL default 0'),
                ('page_namespace', 'int(11) NOT NULL default 0'),
                ('page_title', 'varbinary(255) NOT NULL'),
                ('page_restrictions', 'varbinary(255) default NULL'),
                ('page_counter', 'bigint(20) unsigned NOT NULL default 0'),
                ('page_is_redirect' ,'tinyint unsigned NOT NULL default 0'),
                ('page_is_new tinyint', 'unsigned NOT NULL default 0'),
                ('page_random', 'double unsigned NOT NULL default 0'),
                ('page_touched', "varbinary(14) NOT NULL default ''"),
                ('page_latest', 'int(8) unsigned NOT NULL default 0'),
                ('page_len', 'int(8) unsigned NOT NULL default 0'),
                ('page_no_title_convert', 'tinyint(1) NOT NULL default 0')),
          'revision': (
                ('language', "varbinary(20) NOT NULL default ''"),
                ('day', 'varbinary(8) NOT NULL'),
                ('identifier', 'int(8)'),
                ('rev_id', 'int(8) unsigned NOT NULL default 0'),
                ('rev_page', 'int(8) unsigned NOT NULL default 0'),
                ('rev_text_id', 'int(8) unsigned NOT NULL default 0'),
                ('rev_comment', 'varbinary(400) NOT NULL'),
                ('rev_user', 'int(5) unsigned NOT NULL default 0'),
                ('rev_user_text', "varbinary(255) NOT NULL default ''"),
                ('rev_timestamp', "binary(14) NOT NULL default ''"),
                ('rev_minor_edit', 'tinyint(1) unsigned NOT NULL default 0'),
                ('rev_deleted', 'tinyint unsigned NOT NULL default 0'),
                ('rev_len', 'int(8) unsigned default NULL'),
                ('rev_parent_id', 'int(8) unsigned default NULL'))}
    index_layout = {
          'page':{'idl': ('identifier', 'day', 'language')},
          'revision':{'idl':('identifier', 'language')}}
    
    def __init__(self, day_to_check):
        DatabaseInterface.__init__(self)
        self._explain(
              1,
              "Checking the tables `page' and `revision' for day %s." %
              day_to_check)
        self.__day_to_check = day_to_check
        self.__edit_counter = NoticedArticle(day_to_check)
        #if not self.__edits_are_counted():
        #    self.__edit_counter.count_edits()
        if not self.__day_is_loaded():
            self.tidy_up_page_table()
            self.tidy_up_revision_table()
            self.__read_articles()
            self.__fetch_revisions()
    
    def get_reference_days(self):
        return self.__edit_counter.get_reference_days()
    
    def __day_is_loaded(self):
        contained_in = {'page':False, 'revision':False}
        both_tables = ['page', 'revision']
        for table in both_tables:
            sql_statement = """
                  SELECT DISTINCT day FROM %s""" % table
            SQL_Cursors()['auxiliary'].execute(sql_statement)
            for row in SQL_Cursors()['auxiliary']:
                if row[0] == str(self.__day_to_check):
                    contained_in[table] = True
        result = contained_in['page'] and contained_in['revision']
        if result is True:
            self._explain(1, "Tables `page' and `revision' are up to date.")
        else:
            self._explain(1, "Tables `page' and `revision' need update.")
        return result        
    
    @staticmethod
    def tidy_up_page_table():
        sql_command = """
              DELETE FROM page"""
        SQL_Cursors()['auxiliary'].execute(sql_command)
    
    @staticmethod
    def tidy_up_revision_table():
        sql_command = """
              DELETE FROM revision"""
        SQL_Cursors()['auxiliary'].execute(sql_command)
    
    def __read_articles(self):
        sql_command = """
              SELECT identifier, language, page_id, detected_by_mf,
                    detected_by_cta, detected_by_cts, detected_by_mdf
              FROM noticed_article
              WHERE day = %(day)s""" % {'day':self.__day_to_check}
        SQL_Cursors()['auxiliary'].execute(sql_command)
        self.__articles = SQL_Cursors()['auxiliary'].fetchall()
    
    def __fetch_revisions(self):
        self._explain(
              1,
              "The tables `page' and `revision' are updated. " +
              "This might need some time.")
        reference_days = self.__edit_counter.get_reference_days()
        first_day = reference_days[-1]
        for article in self.__articles:
            #print article
            #sys.exit(0)

#            SQL_Cursors()['auxiliary'].execute("select max(day) from revision where lang = ?", article[1])
                #row= SQL_Cursors()['auxiliary'].fetch()
                #print row
                #article_last_update= langs_last_update[article[1]]= 
                
            self._explain(3, article)
            RevisionFetcher(article[0], article[1], article[2],
                            first_day, self.__day_to_check,
                            article[3], article[4], article[5], article[6])

class ChangedArticle(DatabaseInterface):
    """This class updates the crucial table changed_article that
    contains the result of the program.
    """
    
    table_layout = {
          'changed_article': (
                ('identifier', 'INT(8) DEFAULT NULL'),
                ('day', 'VARBINARY(8) DEFAULT NULL'),
                ('language', "VARBINARY(20) DEFAULT NULL"),
                ('page_id', 'INT(8) UNSIGNED DEFAULT NULL'),
                ('page_title', 'VARBINARY(255) DEFAULT NULL'),
                ('only_usual', 'TINYINT(1) DEFAULT 0'),
                ('no_category', 'TINYINT(1) DEFAULT 0'),
                ('only_major', 'TINYINT(1) DEFAULT 0'),
                ('non_bot', 'TINYINT(1) DEFAULT 0'),
                ('many_user', 'TINYINT(1) DEFAULT 0'),
                ('another_filter', 'TINYINT(1) DEFAULT 0'),
                ('detected_by', 'VARBINARY(3) DEFAULT NULL'))}
    index_layout = {
          'changed_article': {
                ('UNIQUE', 'identifier_day_language_algorithm'):
                      ('identifier', 'day', 'language', 'detected_by')}}
    
    def __init__(self, day):
        DatabaseInterface.__init__(self)
        self.__page_and_revision = PageAndRevision(day)
        self.__day = day
        self.__filters_applied = False
        self._filter_management = FilterManagement(day)  #TODO: Can we remove this?
        self._filters = {}
        self.__tidy_up_database()
        self._filters = {'active':{'WHERE':{},'HAVING':{}}}
        self._read_filters_from_file()
        standard_filters = \
              ('only_usual', 'no_category', 'only_major', 'non_bot',
               'many_user', 'another_filter')
        algorithms = ('mf','cta','cts')
        # The standard_filters are those which have their own column
        # in the sql table. If you add a standard filter, please also
        # make the corresponding change in the class variable
        # table_layout (which is used if the table does not yet exist),
        # and change any existing sql tables by hand.
        articles_updated = 0
        for filter in standard_filters:
            self._filter_management = FilterManagement(day)
            self._filter_management.set_reference_days(
                  self.__page_and_revision.get_reference_days())
            if filter in self._filters['active']['WHERE']:
                self._filter_management.add_where_clause(filter, self._filters['active']['WHERE'][filter])
            elif filter in self._filters['active']['HAVING']:
                self._filter_management.add_having_clause(filter, self._filters['active']['HAVING'][filter])
            elif filter in self._filters['active']['SELECT']:
                self._filter_management.add_select_clause(filter, self._filters['active']['SELECT'][filter])
            else:
                continue
            self.__current_filter = filter
            for algorithm in algorithms:
                self._explain(1, 'applying filter %s with algorithm %s.' %
                      (filter, algorithm))
                articles_updated= articles_updated + self.__write_articles_test(algorithm)
        self._explain(1, "updated %d articles total." % articles_updated)
    
    def _read_filters_from_file(self):
        self._explain(1,'read filters')
        f = file(PATH_TO_THIS_FILE + 'filter.txt')
        for line in f:
            self.__interprete_filer(line)
        f.close()
        try:
            active_filters = self._filters['active']
        except KeyError:
            active_filters = {}
        try:
            active_where_filters = active_filters['WHERE']
        except KeyError:
            active_where_filters = {}
        for name, filter in active_where_filters.iteritems():
            self._filter_management.add_where_clause(name, filter)
            self._explain(1,"adding WHERE condition `%s'" % filter)
        try:
            active_having_filters = active_filters['HAVING']
        except KeyError:
            active_having_filters = {}
        for name, filter in active_having_filters.iteritems():
            self._filter_management.add_having_clause(name, filter)
            self._explain(1,"adding HAVING condition `%s'" % filter)
        try:
            active_select_filters = active_filters['SELECT']
        except KeyError:
            active_select_filters = {}
        for name, filter in active_select_filters.iteritems():
            self._filter_management.add_select_clause(name, filter)
            self._explain(1,"adding SELECT condition `%s'" % filter)
    
    def __interprete_filer(self, line):
        clauses = ['WHERE', 'HAVING', 'SELECT']
        statuses = ['active']
        successful = False
        for status in statuses:
            if line.startswith(status):
                statement = line[len(status):]
                statement= statement.strip()
                if status not in self._filters:
                    self._filters[status] = {}
                name, rest = statement.split(' ', 1)
                statement = rest.strip()
                for clause in clauses:
                    if statement.startswith(clause):
                        statement = statement[len(clause):]
                        statement = statement.strip()
                        try:
                            self._filters[status][clause][name] =statement
                        except KeyError:
                            self._filters[status][clause] = {name: statement}
                        successful = True
                        break
            if successful:
                break
        if not successful:
            if not line.startswith('#') and not line.strip() == '':
                self._explain(1,"WARNING: could not interpret line `%s'" %
                      line.strip())
    
    def old_write_articles(self):
        self._explain(1, '  write articles to database')
        
        for language in self.get_languages():
            self.__write_articles_in(language)
    
    def display_articles(self):
        self.write_articles()
        xml = XMLDisplay(self.__day)
        xml.write_results()
    
    #original write_articles method
    def __write_articles(self, algorithm):
        #self._filter_management.set_language(language)
        self._filter_management.set_algorithm(algorithm)
        parameters = self._filter_management.get_articles()
        self._explain(1, '    writing %d changed articles to database' % len(parameters))
        sql_statement = """
              INSERT INTO changed_article
              (identifier, language, page_id, day, page_title, %(filter)s, detected_by)
              VALUES (?, ?, ?, '%(day)s', ?, 1, '%(algorithm)s')
              ON DUPLICATE KEY
              UPDATE %(filter)s = 1""" % \
              {'filter':self.__current_filter,
               'day':self.__day,
               'algorithm':algorithm}
        self._explain(2, sql_statement)
        SQL_Cursors()['auxiliary'].executemany(sql_statement, parameters)
    
    # testing --
    # returns number of updated articles
    def __write_articles_test(self, algorithm):
        use_start_transaction= 1    # if true, use START TRANSACTION instead of executemany()
        
        self._filter_management.set_algorithm(algorithm)
        parameters = self._filter_management.get_articles()
        self._explain(1, '    writing %d changed articles to database' % len(parameters))
        
        cur= SQL_Cursors()['auxiliary']
        sql_statement = """
              INSERT INTO changed_article
              (identifier, language, page_id, day, page_title, %(filter)s, detected_by)
              VALUES (?, ?, ?, '%(day)s', ?, 1, '%(algorithm)s')
              ON DUPLICATE KEY
              UPDATE %(filter)s = 1""" % \
              {'filter':self.__current_filter,
               'day':self.__day,
               'algorithm':algorithm}
        self._explain(2, sql_statement)
        
        cur.execute("SET PROFILING=1")

        if use_start_transaction:
            self._explain(1, "    using START TRANSACTION")
            cur.execute("START TRANSACTION")
            for p in parameters:
                cur.execute(sql_statement, p)
            cur.execute("COMMIT")
        else:
            self._explain(1, "    using executemany()")
            cur.executemany(sql_statement, parameters)
        
        cur.execute("SET PROFILING=0")
        
        # get profiling information and write to "tmp.csv"
        cur.execute("""
              SELECT 
              query_id, 
              seq, 
              state, 
              duration, 
              cpu_user, 
              cpu_system,
              CONTEXT_VOLUNTARY,
              CONTEXT_INVOLUNTARY,
              BLOCK_OPS_IN,
              BLOCK_OPS_OUT,
              MESSAGES_SENT,
              MESSAGES_RECEIVED,
              PAGE_FAULTS_MAJOR,
              PAGE_FAULTS_MINOR,
              SWAPS,
              SOURCE_FUNCTION,
              SOURCE_FILE,
              SOURCE_LINE
              FROM INFORMATION_SCHEMA.PROFILING ORDER BY query_id""")
        profresult= cur.fetchall()
        writer= csv.writer(open("sql-profiling-lastrun.csv", "wb"))
        for row in profresult:
            writer.writerow(row)

        return len(parameters)
    
    def __tidy_up_database(self):
        sql_statement = """
              DELETE FROM changed_article
              WHERE day = '%s'""" % self.__day
        SQL_Cursors()['auxiliary'].execute(sql_statement)

class CountingAlgorithm(MyObject):
    """Called by NoticedArtice. Formulate and perform the sql
    statements representing the algorithms.
    """
    
    def __init__(self, language):
        self._explain(
              1,
              "Applying counting algorithms for language %s ..." %
              language)
        self.__language = language
        self.__changed_articles = []
        self.__algorithms_applied = False
    
    def set_first_reference_day(self, day):
        self.__first_reference_day = day
    
    def set_last_reference_day(self, day):
        self.__last_reference_day = day
    
    def set_day_to_check(self, day):
        self.__day_to_check = day
    
    def __iter__(self):
        if not self.__algorithms_applied:
            self.__formulate_sql_statements()
            self.__apply_algorithms()
        return self.__changed_articles.__iter__()
    
    def __apply_algorithms(self):
        for name, algorithm in self.__algorithms.iteritems():
            self._explain(1, "  executing algorithm `%s'" % name)
            SQL_Cursors()['auxiliary'].execute(algorithm)
            for id, bla, blabla in SQL_Cursors()['auxiliary']:
                article = Article(
                      self.__language,
                      id,
                      self.__abbreviation[name],
                      1.0)
                self.__changed_articles.append(article)
        self.__algorithms_applied = True
        self._explain(1, """...%d articles found.""" % len(self.__changed_articles))
    
    def __formulate_sql_statements(self):
        self.__algorithms = {}
        self.__algorithms['MaximumFinder'] = """
              SELECT t1.page_id, MAX(t1.edits), t2.edits
              FROM edit_count AS t1
              JOIN edit_count AS t2
              ON t1.page_id = t2.page_id
              WHERE t1.language = '%(language)s'
              AND t2.language = '%(language)s'
              AND t1.day BETWEEN '%(start)s' AND '%(stop)s'
              AND t2.day = '%(day)s'
              GROUP BY t1.page_id
              HAVING 0.9 * MAX(t1.edits) < t2.edits""" % \
              {'language':self.__language,
               'start':self.__first_reference_day,
               'stop':self.__last_reference_day,
               'day':self.__day_to_check}
        self.__algorithms['MaximumDifferenceFinder'] = """
              SELECT
                    today.page_id,
                    MAX(other_days.difference),
                    today.difference
              FROM edit_count AS today
              JOIN edit_count AS other_days
              ON today.page_id = other_days.page_id
              WHERE today.language = '%(language)s'
              AND other_days.language = '%(language)s'
              AND today.day = '%(day)s'
              AND other_days.day BETWEEN '%(start)s' AND '%(stop)s'
              GROUP BY today.page_id
              HAVING 0.9 * MAX(other_days.difference) < today.difference""" % \
              {'language':self.__language,
               'start':self.__first_reference_day,
               'stop':self.__last_reference_day,
               'day':self.__day_to_check}
        self.__algorithms['ComparisonToAverage'] = """
              SELECT t1.page_id, SUM(t1.edits), t2.edits
              FROM edit_count AS t1
              JOIN edit_count AS t2
              ON t1.page_id = t2.page_id
              WHERE t1.language = '%(language)s'
              AND t2.language = '%(language)s'
              AND t1.day BETWEEN %(start)s AND %(stop)s
              AND t2.day = %(day)s
              GROUP BY t1.page_id
              HAVING 1.3 * SUM(t1.edits) / %(reference_days)s < t2.edits""" % \
              {'language':self.__language,
               'start':self.__first_reference_day,
               'stop':self.__last_reference_day,
               'day':self.__day_to_check,
               'reference_days':Settings()['number_of_reference_days']}
        self.__algorithms['ComparisonToStandardDeviation'] = """
              SELECT /* SLOW_OK */ t1.page_id, SUM(t1.edits), t2.edits
              FROM edit_count AS t1
              JOIN edit_count AS t2
              ON t1.page_id = t2.page_id
              WHERE t1.language = '%(language)s'
              AND t2.language = '%(language)s'
              AND t1.day BETWEEN %(start)s AND %(stop)s
              AND t2.day = %(day)s
              GROUP BY t1.page_id
              HAVING SUM(t1.edits) / %(reference_days)s + 1.3 * STD(t1.edits) < t2.edits""" % \
              {'language':self.__language,
               'start':self.__first_reference_day,
               'stop':self.__last_reference_day,
               'day':self.__day_to_check,
               'reference_days':Settings()['number_of_reference_days']}
        self.__abbreviation = {
              'MaximumFinder':'mf',
              'ComparisonToAverage':'cta',
              'ComparisonToStandardDeviation':'cts',
              'MaximumDifferenceFinder':'mdf'}

class EditCountDifference(MyObject):
    """Called by EditCount. It cares that the column 'difference'
    is properly updated in the table edit_count."""
    
    def __init__(self):
        self.__find_days_without_difference()
        for day in self.__days_without_difference:
            self.__try_to_update_differences(day)
            self.__update_remaining_differences(day)

    
    def __find_days_without_difference(self):
        sql_statement = """
              SELECT DISTINCT day
              FROM edit_count"""
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        available_days = []
        for row in SQL_Cursors()['auxiliary']:
            available_days.append(row[0])
        sql_statement = """
              SELECT DISTINCT day
              FROM edit_count
              WHERE difference IS NULL"""
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        self.__days_without_difference = []
        for row in SQL_Cursors()['auxiliary']:
            self.__days_without_difference.append(row[0])
        days_without_yesterday = []
        for day in self.__days_without_difference:
            if not str(Day(day) - 1) in available_days:
                days_without_yesterday.append(day)
        for day in days_without_yesterday:
            self.__days_without_difference.remove(day)
    
    def __try_to_update_differences(self, day):
        yesterday = str(Day(day) - 1)
        sql_statement = """
              UPDATE edit_count AS today, edit_count AS yesterday
              SET today.difference = CONVERT(
                  today.edits - yesterday.edits, SIGNED)
              WHERE today.page_id = yesterday.page_id
              AND today.language = yesterday.language
              AND today.day = '%(day)s'
              AND yesterday.day = '%(yesterday)s'""" % \
              {'day':day,
               'yesterday':yesterday}
        SQL_Cursors()['auxiliary'].execute(sql_statement)
    
    def __update_remaining_differences(self, day):
        sql_statement = """
              UPDATE edit_count
              SET difference = edits
              WHERE day = '%(day)s'
              AND difference IS NULL
              """ % {'day':day}
        SQL_Cursors()['auxiliary'].execute(sql_statement)

class ArticleMerger(MyObject):
    """Collects articles and merges different language versions of the
    same articles.
    
    Articles can be appended with the append() method and can be
    iterated over by using Python's iterator API. The articles in the
    iteration possess the titles in all available language versions.
    When an article is appended several times (e.g. in several
    language versions), this will be recognized and the versions will be
    merged. One should first append all articles and iterate over the
    result afterwards. Mixing of these two steps is possible, but will
    cause the time-consuming merging process to happen several times.
    
    Working with ArticleMerger objects has a side-effect: it will change
    the Article objects that are passed to the ArticleMerger.
    
    As on the metadata database the search for page ids is much more
    reliable than the search for titles, this class relies on the proper
    initialization of Article objects where an initial language and an
    initial id are passed to the object.
    
    In the version ChangeDetector2 a second step was added to the
    merging process: now the page_ids are added to the articles
    after the language versions are merged together.

    """
    
    required_hits = 3
    # Changing this number will only take effect after the entries
    # in the table `noticed_article' are deleted.
    
    def __init__(self):
        self.__articles = []
        self.__sorted_articles = []
        self.__merged = True
    
    def __iter__(self):
        if not self.__merged:
            self.__merge_articles()
        return self.__sorted_articles.__iter__()
    
    def __len__(self):
        return len(self.__sorted_articles)
    
    def append(self, article):
        #if isinstance(article, list):
        #    for item in article:
        #        self.__articles.append(item)
        #else:
        #    self.__articles.append(article)
        self.__articles.append(article)
        self.__merged = False
    
    def __merge_articles(self):
        self._explain(1, 'Merge articles in different language versions.')
        self.__determine_languages_and_ids()
        self.__find_titles()
        self.__complete_article_objects()
        self.__merge_article_objects()
        self.__sort_and_truncate_articles()
        self.__find_ids()
        self._explain(1, '...merging complete. %d articles collected' %
              len(self.__sorted_articles))
    
    def __determine_languages_and_ids(self):
        self.__languages_and_ids = {}
        for article in self.__articles:
            language = article.get_initial_language()
            id = article.get_initial_id()
            try:
                self.__languages_and_ids[language].append(id)
            except KeyError:
                self.__languages_and_ids[language] = [id]
        self.__languages = self.__languages_and_ids.keys()
    
    def __find_titles(self):
        self.__titles = {}
        self.__string_of_all_languages = ', '.join("'%s'" % lang
              for lang in self.__languages)
        for language, ids in self.__languages_and_ids.iteritems():
            self.__latest_ids = ', '.join("%d" % id
                  for id in ids)
            self.__find_titles_in_same_language(language)
            self.__find_titles_in_other_languages(language)
        
    def __complete_article_objects(self):
        articles_without_title = []
        for n, article in enumerate(self.__articles):
            initial_language = article.get_initial_language()
            initial_id = article.get_initial_id()
            try:
                article.title = self.__titles[initial_language][initial_id]
            except KeyError:
                articles_without_title.append(n)
                # Reaction to errors in the database
        self.__titles = None
        # release memory
        for n in reversed(articles_without_title):
            del self.__articles[n]
        
    def __merge_article_objects(self):
        self.__merged_articles = {}
        for article in self.__articles:
            identifier = str(article)
            if identifier in self.__merged_articles:
                other_article = self.__merged_articles[identifier]
                self.__merge_two(article, other_article)
            else:
                self.__merged_articles[identifier] = article
        self.__articles = self.__merged_articles.values()
        self.__merged_articles = None  # release memory
    
    def __find_titles_in_same_language(self, language):
        self.__titles[language] = {}
        sql_command = """
              SELECT page_id, page_title
              FROM page
              WHERE page_id IN (%s)""" % self.__latest_ids
        SQL_Cursors()[language].execute(sql_command)
        for id, title in SQL_Cursors()[language]:
            self.__titles[language][id] = {language:title}
    
    def __find_titles_in_other_languages(self, language):
        sql_command = """
              SELECT ll_lang, ll_from, ll_title
              FROM langlinks
              WHERE ll_lang IN (%s)
              AND ll_from in (%s)""" % \
                    (self.__string_of_all_languages, self.__latest_ids)
        SQL_Cursors()[language].execute(sql_command)
        for other_language, id, title in SQL_Cursors()[language]:
            try:
                self.__titles[language][id][other_language] = title
            except KeyError:
                self._explain(2, "Problem with page id %s of language %s/%s." % (str(id), language, other_language))
        
    def __merge_two(self, article, other_article):
        this_algorithms = article.rating.keys()
        other_algorithms = other_article.rating.keys()
        all_algorithms = set(this_algorithms) | set(other_algorithms)
        for algorithm in all_algorithms:
            try:
                this_languages = article.rating[algorithm].keys()
            except KeyError:
                this_languages = []
            try:
                other_languages = other_article.rating[algorithm].keys()
            except KeyError:
                other_languages = []
            all_languages = set(this_languages) | set(other_languages)
            for language in all_languages:
                try:
                    this_rating = article.rating[algorithm][language]
                except KeyError:
                    this_rating = 0.0
                try:
                    other_rating = other_article.rating[algorithm][language]
                except KeyError:
                    other_rating = 0.0
                overall_rating = max(this_rating, other_rating)
                article.set_rating(
                      language, algorithm, overall_rating)
                other_article.set_rating(
                      language, algorithm, overall_rating)
        #id_languages = set(article.id.keys()) | set(other_article.id.keys())
        #for language in id_languages:
        #    try:
        #        id = article.id[language]
        #    except KeyError:
        #        id = other_article.id[language]
        #    article.id[language] = id
        #    other_article.id[language] = id
    
    #def __old_sort_and_truncate_articles(self):
    #    self__sorted_articles = self.__articles
    
    def __sort_and_truncate_articles(self):
        #self.__sorted_articles = []
        #for article in self.__articles:
        #    for algorithm, languages in article.rating.iteritems():
        #        #if len(languages) >= self.required_hits:
        #        if len(languages) >= ArticleMerger.required_hits:
        #            self.__sorted_articles.append(article)
        #            break
        self.__sorted_articles = sorted(self.__articles)
        #TODO: Only articles with sufficiently many hits should be counted.
    
    def __str__(self):
        strings = []
        for article in self.__sorted_articles:
            strings.append(article)
        return str(strings)
    
    ##################### #TODO reduce the size of this class.
    
    def __find_ids(self):
        self._explain(1, '  searching for the article ids')
        self.__create_list_of_all_titles()
        for language in self.__list_of_all_titles.keys():
            self.__find_ids_in_language(language)
    
    def __create_list_of_all_titles(self):
        self.__list_of_all_titles = {}
        for article in self.__sorted_articles:
            for language, title in article.title.iteritems():
                self.__append_to_list_of_all_titles(language, title)
    
    def __append_to_list_of_all_titles(self, language, title):
        try:
            self.__list_of_all_titles[language].append(title.replace(' ', '_'))
        except KeyError:
            self.__list_of_all_titles[language] = [title.replace(' ', '_')]
    
    def __find_ids_in_language(self, language):
        placeholders = '(' + \
              "?, "*(len(self.__list_of_all_titles[language]) - 1 ) + \
              "?)"
        sql_statement = """
              SELECT /* SLOW_OK */ page_title, page_id
              FROM page
              INNER JOIN langlinks on page.page_id = langlinks.ll_from
              WHERE REPLACE(page_title,' ','_') IN %s""" % placeholders
              # By joining langlinks we should avoid to catch dead
              # entries in the page-table.
        SQL_Cursors()[language].execute(sql_statement, self.__list_of_all_titles[language])
        ids = {}
        for page_title, page_id in SQL_Cursors()[language]:
            title = page_title.replace(' ', '_')
            ids[title] = page_id
        for article in self.__sorted_articles:
            if language in article.title:
                #print article.title
                #print ids
                title = article.title[language].replace(' ','_')
                try:
                    article.id[language] = ids[title]
                except KeyError:
                    pass
                    #self._explain(1,"WARNING: Artile {%s:%s} could not be collected." %
                    #      (language, title))
                    #TODO: switch on again after only articles with three versions are collected.

class Article(object):
    """Wikipedia articles with titles and ratings on a joint topic.
    
    This class represents a class of Wikipedia articles on the same
    topic, i.e. articles that are connected with cross-language links.
    An Article object must initialized with a certain language and its
    page-id in this language and a certain rating algorithm and the
    rating of the version in the given language due to the given
    algorithm. After the initialization one can add titles in several
    languages and ratings according to several algorithms in several
    languages. Article objects that refer to the same class of Wikipedia
    articles share a unique string reprentation.
    
    """
    
    def __init__(self, language, id, algorithm, rating):
        self.__initial_id = id
        self.__initial_language = language
        self.title = {}
        """A dictionary of language codes pointing to the titles of the
        Wikipedia articles in the corresponding language.
        @type: dict"""
        self.id = {language:id}
        """A dictionary of language codes pointing to the page ids of
        the Wikipedia articles in the corresponding language.
        @type: dict"""
        self.rating = {}
        """A nested dictionary; keys are abbreviations of the
        algorithms; values are dictionaries where language codes point
        to the rating in the corresponding language obtained with the
        corresponding algorithm.
        @type: dict"""
        self.set_rating(language, algorithm, float(rating))
        self.__overall_rating = None
    
    def set_rating(self, language, algorithm, rating):
        """This method ensures that the dictionary-key for the algorithm
        is created if it does not exist yet. It should be used instead
        of accessing the instance variable "rating" directly."""
        try:
            self.rating[algorithm][language] = rating
        except (KeyError, AttributeError):
            self.rating[algorithm] = {language:rating}
        self.__set_overall_rating()
    
    def get_initial_id(self):
        return self.__initial_id
    
    def get_initial_language(self):
        return self.__initial_language
    
    def languages(self):
        """Return a list of the language codes where titles exist."""
        return self.title.keys()
                  
    def __str__(self):
        """A unique identifier for all language versions of one article.
        
        To make the identifier unique it is sufficient to use the name
        in one language version.
        
        """
        try:
            first_language = sorted(self.languages())[0]
            first_title = self.title[first_language]
        except IndexError:
            raise ArticleException("""
                  The article has no string representation because it
                  does not yet know any of its titles."""
                  )
        return "%s:%s" % (first_language, first_title.replace(' ', '_'))
        # It seems that ' ' and '_' are treated equivalently in the database.
    
    def __set_overall_rating(self):
        self.overall_rating = 0.0
        for language in self.languages():
            language_rating = 0.0
            for algorithm, rating in self.rating.iteritems():
                try:
                    self.rating[algorithm][language]
                    language_rating = language_rating + 0.01
                except KeyError:
                    pass
            self.overall_rating = self.overall_rating + language_rating
            if language_rating > 0:
                self.overall_rating = self.overall_rating + 1.0
    
    def __cmp__(self, other):
        return other.overall_rating - self.overall_rating

class RevisionFetcher(MyObject):
    """A tool used by the class PageAndRevision to update its tables.
    """
    
    def __init__(self, identifier, language, page_id, first_day, last_day,
          detected_by_mf, detected_by_cta, detected_by_cts, detected_by_mdf):
        #self._explain(1, '# RevisionFetcher')
        self.__identifier = identifier
        self.__page_id = page_id
        self.__language = language
        self.__first_day = first_day
        self.__last_day = last_day
        self.__detected_by_mf = detected_by_mf
        self.__detected_by_cta = detected_by_cta
        self.__detected_by_cts = detected_by_cts
        self.__detected_by_mdf = detected_by_mdf
        self.__read_page_properties()
        if self.__page is not None:
            self.__write_page_properties()
            self.__read_revision_properties()
            self.__write_revision_properties()
    
    def __read_page_properties(self):
        sql_command = """SELECT *
            FROM page WHERE page_id = ?"""
        SQL_Cursors()[self.__language].execute(
              sql_command, [self.__page_id])
        sql_result = SQL_Cursors()[self.__language].fetchall()
        try:
            self.__page = sql_result[0]
        except IndexError:
            self.__page = None
        if self.__page is not None and len(self.__page) == 11:
            self.__page = list(self.__page)
            self.__page.append(0)
            # Some language versions have no `page_no_title_convert'-entry.
    
    def __write_page_properties(self):
        sql_command = """
              INSERT INTO page (
              language,
              day,
              identifier,
              page_id,
              page_namespace,
              page_title,
              page_restrictions,
              page_counter,
              page_is_redirect,
              page_is_new,
              page_random,
              page_touched,
              page_latest,
              page_len,
              page_no_title_convert,
              detected_by_mf,
              detected_by_cta,
              detected_by_cts,
              detected_by_mdf)
              VALUES ('%(language)s', '%(day)s', %(identifier)s,
                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                      %(mf)s, %(cta)s, %(cts)s, %(mdf)s)""" % \
              {'language':self.__language,
               'day':self.__last_day,
               'identifier':self.__identifier,
               'mf':self.__detected_by_mf,
               'cta':self.__detected_by_cta,
               'cts':self.__detected_by_cts,
               'mdf':self.__detected_by_mdf}
        SQL_Cursors()['auxiliary'].execute(sql_command, self.__page)
    
    def __read_revision_properties(self):
        SQL_Cursors()['auxiliary'].execute("select max(day) from revision")
        lastrevday= SQL_Cursors()['auxiliary'].fetchone()
        print("max(day) for page_id %d: %s" % (self.__page_id, lastrevday))
        sys.stdout.flush()
        if lastrevday == None: lastrevday= self.__first_day
        else: lastrevday= lastrevday[0]
        
        sql_command = """SELECT rev_id, rev_page, rev_text_id, rev_comment, rev_user, rev_user_text, 
              rev_timestamp, rev_minor_edit, rev_deleted, rev_len, rev_parent_id 
              FROM revision
              WHERE rev_page = ?
              AND rev_timestamp 
              BETWEEN '%(start)s000000' AND '%(stop)s999999' 
              """ % {'start':lastrevday,'stop':self.__last_day}
        SQL_Cursors()[self.__language].execute(
              sql_command, [self.__page_id])
        sql_result = SQL_Cursors()[self.__language].fetchall()
        self.__revision = sql_result
    
    def __write_revision_properties(self):
        sql_command = """
              INSERT INTO revision (
              language,
              day,
              identifier,
              rev_id,
              rev_page,
              rev_text_id,
              rev_comment,
              rev_user,
              rev_user_text,
              rev_timestamp,
              rev_minor_edit,
              rev_deleted,
              rev_len,
              rev_parent_id)
              VALUES ('%(language)s', '%(day)s', %(identifier)s,
                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)""" % \
              {'language':self.__language,
               'day':self.__last_day,
               'identifier':self.__identifier}
        SQL_Cursors()['auxiliary'].executemany(
              sql_command, self.__revision)

class FilterManagement(MyObject):
    """This class constructs a query."""
    
    __Bots = None
    __Categories = None
    
    def __init__(self, day):
        self.__day = day
        self.__where_clauses = {}
        self.__where_filter = ''
        self.__select_filter = ''
        self.__having_clauses = {}
        self.__algorithms = None
        self.__filters_applied = False
        if FilterManagement.__Bots is None:
            self.__create_bot_list()
        self.__bot_string = "('%s')" % "','".join(self.__Bots)
        if FilterManagement.__Categories is None:
            self.__create_category_list()
        self.__category_string = "('%s')" % "','".join(FilterManagement.__Categories)
        
    
    def set_reference_days(self, days):
        self.__reference_days = days
        self.__min_timestamp = "%s000000" % self.__reference_days[-1]
        self.__max_timestamp = "%s999999" % self.__reference_days[0]
    
    def set_language(self, language):
        self.__language = language
    
    def add_where_clause(self, name, string):
        #self.__where_clauses[name] = \
        #      string.replace('{bots}', self.__bot_string).replace('{categories}', self.__category_string)
        #self.__filters_applied = False
        self.__where_filter = 'AND ' + string.replace(
              '{bots}', self.__bot_string).replace(
              '{categories}', self.__category_string)
        self.__filters_applied = False
    
    def add_having_clause(self, name, string):
        self.__having_clauses[name] = \
              string.replace('{bots}', self.__bot_string).replace('{categories}', self.__category_string)
        self.__filters_applied = False
    
    def add_select_clause(self, name, string):
        self.__select_filter = ', ' + string.replace(
              '{bots}', self.__bot_string).replace(
              '{categories}', self.__category_string)
        self.__filters_applied = False
    
    def get_articles(self):
        """Returns a list containing the lists
        [identifier, language, page_id, title]
        """
        if not self.__filters_applied:
            self.__create_sql_statement()
            self.__apply_filters()
        return self.__articles
    
    def set_algorithm(self, algorithm):
        self.__algorithm = algorithm
    
    def __iter__(self):
        """Returns items containing the lists
        [page_id, identifier, title]
        """
        if not self.__filters_applied:
            self.__apply_filters()
        return self.__articles.__iter__()
    
    def __create_bot_list(self):
        FilterManagement.__Bots = []
        sql_statement = """
              SELECT user.user_name
              FROM user_groups
              JOIN user ON user.user_id = user_groups.ug_user
              WHERE user_groups.ug_group = 'bot'"""
        for language in Settings()['languages']:
            SQL_Cursors()[language].execute(sql_statement)
            for row in SQL_Cursors()[language]:
                if row[0].count("'") == 0:
                    FilterManagement.__Bots.append(row[0].replace("'","\'"))
                #This ignores five bots.
        self._explain(
              1,
              'List of %d bots loaded.' % len(FilterManagement.__Bots))
        self._explain(0, 'Bots are %s' % str(FilterManagement.__Bots))
    
    def __create_category_list(self):
        """Fetch the names of all categories"""
        sql_statement = """
              SELECT page_title FROM page
              WHERE day = %s""" % self.__day
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        title_list = SQL_Cursors()['auxiliary'].fetchall()
        clean_titles = []
        for title in title_list:
            if title[0].count("'") == 0:
                clean_titles.append(title[0].replace(' ','_'))
        title_string = "','".join(clean_titles)
        sql_statement = """
              SELECT cat_title FROM category
              WHERE cat_title IN ('%s')""" % title_string
        FilterManagement.__Categories = []
        for language in Settings()['languages']:
            SQL_Cursors()[language].execute(sql_statement)
            sql_result = SQL_Cursors()[language].fetchall()
            for row in sql_result:
                FilterManagement.__Categories.append(row[0])
    
    def __apply_filters(self):
        sql_statement = self.__algorithms[self.__algorithm]
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        self.__sql_result = SQL_Cursors()['auxiliary'].fetchall()
        if SQL_Cursors()['auxiliary'].rowcount == 0:
            self.__articles = []
        else:
            self.__find_titles()
    
    def __new_create_sql_statement(self):
        pass
        
    def __create_sql_statement(self):
        self.__algorithms = {
        'mf': """
              SELECT /* SLOW_OK */ table_2.id_2 AS the_id,
              table_2.identifier,
              table_2.language
              FROM (
                SELECT other_days.id_1 AS id_2,
                MAX(other_days.changes_1) AS changes_max,
                other_days.identifier,
                other_days.language
                FROM (
                  SELECT DISTINCT rev_page AS id_1,
                  LEFT(rev_timestamp,8) AS day_1,
                  COUNT(rev_page) AS changes_1,
                  revision.identifier,
                  revision.language
                  %(select_filter)s
                  FROM revision
                  JOIN page ON revision.identifier = page.identifier
                  AND revision.language = page.language
                  WHERE rev_timestamp BETWEEN %(min_timestamp)s AND %(max_timestamp)s
                  %(where-filter)s
                  GROUP BY identifier, day_1, language
                ) AS other_days
                GROUP BY id_1
              ) AS table_2,
              ( SELECT DISTINCT rev_page AS id_3,
                COUNT(rev_page) AS changes_today,
                revision.identifier,
                revision.language
                %(select_filter)s
                FROM revision
                JOIN page ON revision.identifier = page.identifier
                AND revision.language = page.language
                WHERE LEFT(rev_timestamp,8) = %(day_to_check)s
                %(where-filter)s
                GROUP BY identifier, language
              ) AS table_3
              WHERE table_2.id_2 = table_3.id_3
              AND table_2.identifier = table_3.identifier
              AND table_2.language = table_3.language
              AND 0.9 * table_2.changes_max < table_3.changes_today;""" % \
              {"min_timestamp":self.__min_timestamp, 
              "max_timestamp":self.__max_timestamp, "day_to_check":self.__day,
              'where-filter':self.__where_filter,
              'select_filter':self.__select_filter},
        'cta': """
              SELECT /* SLOW_OK */ table_2.id_2 AS the_id,
              table_2.identifier,
              table_2.language
              FROM (
                SELECT other_days.id_1 AS id_2,
                SUM(other_days.changes_1) AS changes_sum,
                other_days.identifier,
                other_days.language
                FROM (
                  SELECT DISTINCT rev_page AS id_1,
                  LEFT(rev_timestamp,8) AS day_1,
                  COUNT(rev_page) AS changes_1,
                  revision.identifier,
                  revision.language
                  %(select_filter)s
                  FROM revision
                  JOIN page ON revision.identifier = page.identifier
                  AND revision.language = page.language
                  WHERE rev_timestamp BETWEEN %(min_timestamp)s AND %(max_timestamp)s
                  %(where-filter)s
                  GROUP BY identifier, day_1, language
                ) AS other_days
                GROUP BY id_1
              ) AS table_2,
              ( SELECT DISTINCT rev_page AS id_3,
                COUNT(rev_page) AS changes_today,
                revision.identifier,
                revision.language
                %(select_filter)s
                FROM revision
                JOIN page ON revision.identifier = page.identifier
                AND revision.language = page.language
                WHERE LEFT(rev_timestamp,8) = %(day_to_check)s
                %(where-filter)s
                GROUP BY identifier, language
              ) AS table_3
              WHERE table_2.id_2 = table_3.id_3
              AND table_2.identifier = table_3.identifier
              AND table_2.language = table_3.language
              AND 1.3 * table_2.changes_sum / %(reference_days)s < table_3.changes_today;""" % \
              {"min_timestamp":self.__min_timestamp, 
              "max_timestamp":self.__max_timestamp, "day_to_check":self.__day,
              'where-filter':self.__where_filter,
              'select_filter':self.__select_filter,
              'reference_days':len(self.__reference_days)},
        'cts': """
              SELECT /* SLOW_OK */ table_2.id_2 AS the_id,
              table_2.identifier,
              table_2.language
              FROM (
                SELECT other_days.id_1 AS id_2,
                SUM(other_days.changes_1) AS changes_sum,
                STD(other_days.changes_1) AS changes_std,
                other_days.identifier,
                other_days.language
                FROM (
                  SELECT DISTINCT rev_page AS id_1,
                  LEFT(rev_timestamp,8) AS day_1,
                  COUNT(rev_page) AS changes_1,
                  revision.identifier,
                  revision.language
                  %(select_filter)s
                  FROM revision
                  JOIN page ON revision.identifier = page.identifier
                  AND revision.language = page.language
                  WHERE rev_timestamp BETWEEN %(min_timestamp)s AND %(max_timestamp)s
                  %(where-filter)s
                  GROUP BY identifier, day_1, language
                ) AS other_days
                GROUP BY id_1
              ) AS table_2,
              ( SELECT DISTINCT rev_page AS id_3,
                COUNT(rev_page) AS changes_today,
                revision.identifier,
                revision.language
                %(select_filter)s
                FROM revision
                JOIN page ON revision.identifier = page.identifier
                AND revision.language = page.language
                WHERE LEFT(rev_timestamp,8) = %(day_to_check)s
                %(where-filter)s
                GROUP BY identifier, language
              ) AS table_3
              WHERE table_2.id_2 = table_3.id_3
              AND table_2.identifier = table_3.identifier
              AND table_2.language = table_3.language
              AND table_2.changes_sum / %(reference_days)s + 1.3 * table_2.changes_std < table_3.changes_today;""" % \
              {"min_timestamp":self.__min_timestamp, 
              "max_timestamp":self.__max_timestamp, "day_to_check":self.__day,
              'where-filter':self.__where_filter,
              'select_filter':self.__select_filter,
              'reference_days':len(self.__reference_days)}
        }
    
    def __old_create_sql_statement(self):
        select = SelectStatement()
        select.table = 'page'
        select.joins.append(
              'revision ON page.page_id = revision.rev_page AND page.language = revision.language')
        #select.joins.append(
        #      'page AN past_page ON page.page_id = past_page.page_id')
        #select.joins.append(
        #      'revision AS past_revision ON page.page_id = past_revision.rev_page')
        select.items.append('DISTINCT page.page_id')
        select.items.append('page.identifier')
        select.where_conditions.append("page.language = '%s'" % self.__language)
        #select.where_conditions.append("revision.language = '%s'" % self.__language)
        select.where_conditions.append("page.day = '%s'" % self.__day)
        select.group_by_clauses.append('page.page_id')
        select.group_by_clauses.append('page.language')
            #TODO: this contidion might be removed.
        #select.where_conditions.append("page.page_namespace = 0")
        for name in self.__where_clauses:
            select.where_conditions.append(self.__where_clauses[name])
        for name in self.__having_clauses:
            select.having_clauses.append(self.__having_clauses[name])
        self.__sql_statement = str(select)
    
    def __find_titles(self):
        self.__articles = []
        ids = []
        for row in self.__sql_result:
            ids.append(row[0])
        string_of_ids = ','.join( "%s" % id for id in ids)
        sql_command = """
              SELECT page_id, identifier, language, page_title
              FROM page
              WHERE page_id in (%s)""" % string_of_ids
        SQL_Cursors()['auxiliary'].execute(sql_command)
        sql_titles = SQL_Cursors()['auxiliary'].fetchall()
        titles = {}
        for row in sql_titles:
            page_id = row[0]
            identifier = row[1]
            language = row[2]
            page_title = row[3]
            try:
                titles[identifier][language] = page_title
            except KeyError:
                titles[identifier] = {language: page_title}
        for row in self.__sql_result:
            page_id = row[0]
            identifier = row[1]
            language = row[2]
            title = titles[identifier][language]
            self.__articles.append((identifier, language, page_id, title))

class RemoveFilteredArticle(object):
    """Representation of an article that was detected by the algorithms.
    
    The detected articles are all stored in the sql-table
    changed_article. Furthermore, in the sql-table actual_changes,
    those articles are stored that have really changed according to the
    user's opinion. This class helps to access and to connect these
    articles. #TODO: access to actual_changes not yet implemennted.
    """
    
    __table_columns = [
          'identifier', 'day', 'language', 'page_id', 'page_title']
    __preferred_languages = []
    
    def __init__(self, *parameters):
        if len(parameters) == 2:
            self.__construct_with_identifier(parameters)
        else:
            raise TypeError(
                  "DetectedArticle.__init__() takes exactly 2 " +
                  "arguments (%d given)" % len(parameters))
    
    def __str__(self):
        if len(self.__sql_rows) == 0:
            return "DetectedArticle()"
        language_and_title = None
        for language in FilteredArticle.__preferred_languages:
            try:
                language_and_title = {
                      language:self.__sql_rows[language]['page_title']}
                break
            except KeyError:
                continue
        if language_and_title is None:
            arbitrary_language = self.__sql_rows.keys()[0]
            language_and_title = {
                  arbitrary_language:
                  self.__sql_rows[arbitrary_language]['page_title']}
        return "DetectedArticle(%s)" % language_and_title
    
    def __hash__(self):
        return(hash(self.__str__()))
    
    def __eq__(self, other):
        return str(self) == str(other)
    
    @staticmethod
    def set_preferred_languages(language_list):
        """This changes the list of preferred languages for  A L L
        FilteredArticle-objects!"""
        FilteredArticle.__preferred_languages = language_list
    
    def get_day(self):
        return self.__day
    
    def __construct_with_identifier(self, parameters):
        self.__day = parameters[0]
        sql_statement = """
              SELECT * FROM changed_article
              WHERE day = ?
              AND identifier = ?"""
        SQL_Cursors()['auxiliary'].execute(sql_statement, parameters)
        self.__sql_rows = {}
        for row in SQL_Cursors()['auxiliary']:
            language = row[2]
            self.__sql_rows[language] = \
                  dict(zip(self.__table_columns, row))

class RemoveFilteredArticleCollection(MyObject, set):
    """A set of FilteredArticle objects.
    
    An object is initialized with only one filter. Using set operations
    one can construct objects that take account of several filters.
    
    The object can be called in an iteration for each day, see __iter__().
    
    Only articles with more than three hits in different language versions
    will be contained in the object.
    
    Two objects can be compared. The one containing more
    FilteredArticle objects will be considered as the larger one.
    
    #TODO: if days are given that are not listed in the sql-tables
    changed_article and page and revision, this will produce errors.
    """
    
    def __init__(self, days):
        self.__days = days
        self.__articles = []
        """@type list of tuples
        @param the articles represented as
        (<day>, <FilteredArticle-object>).
        """
        self.__article_candidates = {}
        """FilteredArticle objects point to number of hits."""
        self.__filter_management = {}
        self.__construct_filter_managements()
        self.__current_day = None
        """Needed for iteration."""
    
    def apply_filter(self, clause, sql_snippet):
        self.__initial_clause = clause
        self.__initial_sql_snippet = sql_snippet
        for day in self.__days:
            for language in Settings()['languages']:
                self.__get_article_candidates(day, language)
        self.__select_articles_with_three_hits()
        self._explain(1, "%d articles collected." % len(self.__articles))
    
    def append(self, article):
        if not article in self.__articles:
            self.__articles.append(article)
    
    def get_days(self):
        return self.__days
    
    def __call__(self, day):
        """This is required for iterations like
        "for x in articles('20120122'):"
        """
        self.__current_day = day
        return self
    
    def __iter__(self):
        if self.__current_day is None:
            return self.__articles.__iter__()
        else:
            articles = []
            for article in self.__articles:
                if article[0] == self.__current_day:
                    articles.append(article[1])
            self.__current_day = None
            return articles.__iter__()
    
    def __len__(self):
        return len(self.__articles)
    
    def __cmp__(self, other):
        return len(self) - len(other)
    
    def __xor__(self, other):
        days = set(self.get_days()) ^ set(other.get_days())
        #if day != other.get_day():
        #    raise ChangeDetectorException(
        #          "Set operation on articles with diferent days forbidden.")
        result = FilteredArticleCollection(days)
        self_articles = []
        for article in self:
            self_articles.append(article)
        other_articles = []
        for article in other:
            other_articles.append(article)
        common_articles = set(self_articles) ^ set(other_articles)
        for article in common_articles:
            result.append(article)
        return result

    def __and__(self, other):
        days = set(self.get_days()) | set(other.get_days())
        #if day != other.get_day():
        #    raise ChangeDetectorException(
        #          "Set operation on articles with diferent days forbidden.")
        result = FilteredArticleCollection(days)
        self_articles = []
        for article in self:
            self_articles.append(article)
        other_articles = []
        for article in other:
            other_articles.append(article)
        common_articles = set(self_articles) & set(other_articles)
        for article in common_articles:
            result.append(article)
        return result
    
    def add(self, element): raise NotImplementedError
    def remove(self, element): raise NotImplementedError
    def discard(self, element): raise NotImplementedError
    def pop(self): raise NotImplementedError
    def __construct_filter_managements(self):
        for day in self.__days:
            self.__filter_management[day] = {}
            for language in Settings()['languages']:
                self.__filter_management[day][language] = \
                      FilterManagement(day)
                self.__filter_management[day][language].set_language(
                      language)
    
    def __add_clause(self, filter_management):
        if self.__initial_clause == 'WHERE':
            filter_management.add_where_clause(
                  'noname', self.__initial_sql_snippet)
        elif self.__initial_clause == 'HAVING':
            filter_management.add_having_clause(
                  'noname', self.__initial_sql_snippet)
    
    def __get_article_candidates(self, day, language):
        self.__add_clause(self.__filter_management[day][language])
        for article in self.__filter_management[day][language]:
            article_object = FilteredArticle(day, article[1])
            self.__add_article_candidate(article_object)
    
    def __add_article_candidate(self, article_object):
        if article_object in self.__article_candidates:
            self.__article_candidates[article_object] = \
                  self.__article_candidates[article_object] + 1
        else:
            self.__article_candidates[article_object] = 1
    
    def __select_articles_with_three_hits(self):
        for article, hits in self.__article_candidates.iteritems():
            if hits >= 3:
                day = article.get_day()
                self.__articles.append((day, article))


class MyOursqlCursor(oursql.Cursor, MyObject):
    """When choosing a hight enough DebugLevel in change.ini, this class
    will display all SQL commands."""
    def execute(self, operation, parameters=None):
        if operation.rfind('INSERT') == -1:
            self._explain(2, operation)
        else:
            self._explain(3, operation)
            self._explain(3, parameters)
        if parameters is None:
            super(MyOursqlCursor, self).execute(
                  operation, plain_query=True)
        else:
            super(MyOursqlCursor, self).execute(operation, parameters)
    
    def fetchall(self):
        results = super(MyOursqlCursor, self).fetchall()
        self._explain(4, results)
        return results

class Day(object):
    """A class to deal with Wikipedia's day format.
    
    In the tables on the toolserver times are stored as yyyymmdd.
    Instances of this class can be initialized with this format and
    will show it as their string representation. The operators + and
    - are overloaded such that it becomes easy to go a certain number
    of days forward or backward.
    
    Example:
    >>> day = Day('20120115')
    >>> print day + 1
    20120116
    """
    
    __seconds_per_day = 86400
    
    def __init__(self, day=None):
        if isinstance(day, (str, Day)):
            self.__day_string = str(day)
            self.__day_in_seconds = None
        elif isinstance(day, (int, float)):
            self.__day_in_seconds = day
            self.__day_string = None
        elif day == None:
            self.__day_in_seconds = int(time.time())
            self.__day_string = None
        else:
            raise InvalidDayFormatException(str(day))
    
    def __str__(self):
        return self.__get_day_string()
    
    def __add__(self, x):
        new_day_in_seconds = \
              self.__get_day_in_seconds() + x * Day.__seconds_per_day
        return Day(new_day_in_seconds)
    
    def __sub__(self, x):
        new_day_in_seconds = \
              self.__get_day_in_seconds() - x * Day.__seconds_per_day
        return Day(new_day_in_seconds)
    
    def __hash__(self):
        return hash('<day object %s>' % self.__str__())
    
    def __eq__(self, other):
        return str(self) == str(other)
    
    #def __cmp__(self, other):
    #    return int(str(self)) - int(str(other))
    
    def __get_day_string(self):
        if self.__day_string is None:
            self.__convert_to_string()
        return self.__day_string
    
    def __get_day_in_seconds(self):
        if self.__day_in_seconds is None:
            self.__convert_to_seconds()
        return self.__day_in_seconds
    
    def __convert_to_seconds(self):
        try:
            self.__day_in_seconds = time.mktime(
                  time.strptime(self.__day_string, "%Y%m%d"))
        except ValueError:
            raise InvalidDayFormatException(self.__day_string)
    
    def __convert_to_string(self):
        try:
            day_as_python_time = time.localtime(self.__day_in_seconds)
            self.__day_string = time.strftime("%Y%m%d", day_as_python_time)
        except ValueError:
            raise InvalidDayFormatException(self.__day_in_seconds)

class Toolserver_SQL_Cursors(dict, MyObject):
    """Provides a dictionary where the language keys determined in the
    ini-file point to oursql-cursors. With the special key 'auxiliary'
    the auxiliary database can be accessed.
    """
    
    _server_names = None
    _cursors = None
    _auxiliary_cursor = None
    
    def __init__(self):
        if Toolserver_SQL_Cursors._cursors is None:
            self._create_cursors()
    
    def __missing__(self, key):
        if key == 'auxiliary':
            return Toolserver_SQL_Cursors._auxiliary_cursor
        else:
            return self._get_language_cursor(key)
    
    def keys(self):
        keys = ['auxiliary']
        for database_name in Toolserver_SQL_Cursors._server_names.keys():
            keys.append(database_name[:-6])
        return keys
    
    def _get_language_cursor(self, language):
        database_name = "%swiki_p" % language
        try:
            server_name = Toolserver_SQL_Cursors._server_names[database_name]
        except KeyError:
            raise MyOursqlException("""
                  You try to access a Wikipedia with language code `%s'.
                  There is no such language version.""" % language)
        cursor = Toolserver_SQL_Cursors._cursors[server_name]
        self._direct_cursor(cursor, database_name)
        return cursor
    
    def _create_cursors(self):
        self._explain(1, """SQL cursors are created.""")
        self._create_auxiliary_cursor()
        self._find_all_servers()
        self._create_all_connections()
        #Toolserver_SQL_Cursors._cursors = {}
        #Toolserver_SQL_Cursors._server_names = {}
        self._direct_auxiliary_cursor()
        self._explain(3, "  Cursors created to databases %s." % 
              Toolserver_SQL_Cursors._server_names)
    
    def _create_auxiliary_cursor(self):
        auxiliary_connection = oursql.connect(
              read_default_file='~/.my.cnf',
              use_unicode=False
              )
        Toolserver_SQL_Cursors._auxiliary_cursor = MyOursqlCursor(auxiliary_connection)
        #Toolserver_SQL_Cursors._auxiliary_cursor = oursql.Cursor(auxiliary_connection)
    
    def _find_all_servers(self):
        sql_command = """
              SELECT dbname, server
              FROM toolserver.wiki
              WHERE family = 'wikipedia'"""
        Toolserver_SQL_Cursors._auxiliary_cursor.execute(sql_command)
        Toolserver_SQL_Cursors._server_names = \
              dict(Toolserver_SQL_Cursors._auxiliary_cursor.fetchall())
    
    def _create_all_connections(self):
        Toolserver_SQL_Cursors._cursors = {}
        for server in set(Toolserver_SQL_Cursors._server_names.values()):
            self._explain(1, 'Create connection to sql-s%s' % server)
            connection = oursql.connect(
                  host="sql-s%d" % server,
                  read_default_file="~/.my.cnf",
                  charset=None,
                  use_unicode=False)
            cursor = MyOursqlCursor(connection)
            Toolserver_SQL_Cursors._cursors[server] = cursor
    
    def _direct_auxiliary_cursor(self):
        database = Settings()['database']
        try:
            self._direct_cursor(Toolserver_SQL_Cursors._auxiliary_cursor, database)
        except MyOursqlException:
            Toolserver_SQL_Cursors._auxiliary_cursor.execute("""
                  CREATE DATABASE %s""" % database)
            Toolserver_SQL_Cursors._auxiliary_cursor.execute("""
                  USE %s""" % database)
        
    def _direct_cursor(self, cursor, database_name):
        sql_command = """
              USE %s""" % database_name
        try:
            cursor.execute(sql_command)
        except oursql.ProgrammingError:
            raise MyOursqlException("""
                  You try to access a Wikipedia with language code `%s'.
                  There is no such language version.""" % 
                  database_name)


class Toolserver_Settings(dict):
    """This class provides access to variables that are needed
    everywhere in the program. When it is called first, it is
    initialized using the contents of the file `change.ini'.
    This class implements roughly the Borg pattern.
    
    (The standard implementation of the Borg pattern deals with the
    variable __dict__ that is inherited from object and is an instance
    of dict. A dict itself has, of course, no built-in dict-object, so
    the Borg pattern has to be realized in a different way here.)
    
    One could use a global moule variable instead of the
    Borg pattern. But then one would have to take care everywhere in
    the code if the variable is initialized already.
    """
    
    __settings = {}
    __settings_read = False
    
    def __init__(self):
        """Read the ini-file, if not done yet and create a dictionary
        that contains the ini-file's content as well as all other
        parameters that have been passed to any Settings-object before.
        """
        
        if not Toolserver_Settings.__settings_read:
            self.__read_ini_file()
            Toolserver_Settings.__settings_read = True
            self.__customize_dictionary_structure()
    
    def append(self, pair):
        """Use this method to make changes accessable to future calls."""
        for key, value in pair.iteritems():
            Toolserver_Settings.__settings[key] = value

    def __missing__(self, key):
        try:
            return Toolserver_Settings.__settings[key]
        except KeyError:
            raise SettingsException("""
                  The key `%s' is missing in the settings object.
                  Maybe the file `change.ini' is not complete.""" % key )
    
    def __read_ini_file(self):
        parser = ConfigParser.ConfigParser()
        iniFile = PATH_TO_THIS_FILE + 'change.ini'
        if not os.path.exists(iniFile):
            raise ChangeDetectorException(
                  "A file with the absolute path %s does not exist. " % iniFile +
                  "Please adopt the global variable PATH_TO_THIS_FILE " +
                  "or place the file `change.ini' in the apropriate folder.")
        parser.read(iniFile)
        Toolserver_Settings.__settings = {}
        items = parser.items('ChangeDetector')
        for key, value in items:
            self.append({key:value})
    
    def __customize_dictionary_structure(self):
        self.append({'languages': Toolserver_Settings.__settings['languages'].rsplit()})

class SelectStatement(object):
    """This class is not really necessary at the current state of the
    project, but might be helpful when extending it. At the moment it
    is used only by the class FilterManagement.
    """
    
    def __init__(self):
        self.table = ''
        self.items = []
        self.joins = []
        self.where_conditions = []
        self.group_by_clauses = []
        self.having_clauses = []
    
    def __str__(self):
        self.__statement = []
        self.__add_select_items()
        self.__add_table()
        self.__add_joins()
        self.__add_where_conditions()
        self.__add_group_by_clauses()
        self.__add_having_clauses()
        return ' '.join(self.__statement)
    
    def __add_select_items(self):
        self.__statement.append('SELECT')
        self.__statement.append(','.join(self.items))
    
    def __add_table(self):
        self.__statement.append('\nFROM')
        self.__statement.append(self.table)
    
    def __add_joins(self):
        if len(self.joins) > 0:
            self.__statement.append('\nJOIN')
            self.__statement.append(' \nJOIN '.join(self.joins))
    
    def __add_where_conditions(self):
        if len(self.where_conditions) > 0:
            self.__statement.append('\nWHERE')
            self.__statement.append(
                  ' \nAND '.join(self.where_conditions))
    
    def __add_group_by_clauses(self):
        if len(self.group_by_clauses) > 0:
            self.__statement.append('\nGROUP BY')
            self.__statement.append(', '.join(self.group_by_clauses))
    
    def __add_having_clauses(self):
        if len(self.having_clauses) > 0:
            self.__statement.append('\nHAVING')
            self.__statement.append(' \nAND '.join(self.having_clauses))


class ChangeDetectorException(Exception):
    def __init__(self, message):
        self.__message = message
    def __str__(self):
        return self.__message

class SettingsException(ChangeDetectorException):
    pass
    
class ArticleException(Exception):
    def __init__(self, message):
        self.__message = message
    def __str__(self):
        return self.__message

class NoSuchEditAnalyzerException(Exception):
    def __init__(self, name):
        self.__detector_name = name
    def __str__(self):
        return """
              You are trying to use the EditAnalyzer
              `%s'
              which is not implemented.""" % self.__detector_name

class NoSuchKindOfEditException(Exception):
    def __init__(self, kind_of_edit):
        self.__kind_of_edit = kind_of_edit
    def __str__(self):
        return """
              You are trying to use a SimpleEditDetector with the
              kind of edit `%s'.
              Please use `edits', `major_edits' od `non_robot_edits'
              instead.
              """ % self.__kind_of_edit



class InvalidDayFormatException(Exception):
    def __init__(self, day):
        self.__day = day
    def __str__(self):
        return """
              You are trying to call the ChangeFinder for the day
              `%s'
              which cannot be interpreted by this program.
              Please use the format YYYYmmdd, e.g.
              20110509 for September 5 in 2011.""" % self.__day



class ChangeDisplay(MyObject):
    """The base class for displaying the found articles."""
    def __init__(self, day):
        self._day = day
        self._settings = Settings()
        self._web_folder = self._settings['webfolder']
    
    def write_results(self):
        """This method should be implemented by subclasses."""
    
    def _fetch_articles(self):
        sql_statement = """
              SELECT * FROM changed_article
              WHERE day = '%s'""" % self._day
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        self._articles = {}
        for row in SQL_Cursors()['auxiliary']:
            identifier = row[0]
            language = row[2]
            title = row[4]
            try:
                self._articles[identifier][language] = (title, 'changed')
            except KeyError:
                self._articles[identifier] = {language:(title, 'changed')}
        self._fetch_titles()
        self._sort_articles()
    
    def _fetch_titles(self):
        identifiers = self._articles.keys()
        identifier_string = ','.join("%d" % id for id in identifiers)
        sql_statement = """
              SELECT identifier, language, page_title
              FROM page
              WHERE day = '%s'
              AND identifier IN (%s)""" % (self._day, identifier_string)
        SQL_Cursors()['auxiliary'].execute(sql_statement)
        for row in SQL_Cursors()['auxiliary']:
            identifier = row[0]
            language = row[1]
            title = row[2]
            if not language in self._articles[identifier]:
                self._articles[identifier][language] = (title, 'not changed')
    
    def _sort_articles(self):
        hits_and_articles = {}
        for id, article in self._articles.iteritems():
            number_of_hits = self.__get_number_of_hits(article)
            try:
                hits_and_articles[number_of_hits].append(article)
            except KeyError:
                hits_and_articles[number_of_hits] = [article]
        self._sorted_articles = []
        for hits in sorted(hits_and_articles.keys(), reverse=True):
            for article in hits_and_articles[hits]:
                self._sorted_articles.append(article)
        self._explain(2, 'Sorted articles: %s' % hits_and_articles)
    
    @staticmethod
    def __get_number_of_hits(article):
        number_of_hits = 0
        for language, title in article.iteritems():
            if title[1] == 'changed':
                number_of_hits = number_of_hits + 1
        return number_of_hits

class XMLDisplay(ChangeDisplay):
    
    def write_results(self):
        self._fetch_articles()
        yesterday = str(Day(self._day) - 1)
        tomorrow = str(Day(self._day) + 1)
                
        #algorithms = {'mf50':'MaximumFinder_edits_50',
        #              'mf_m50':'MaximumFinder_major_edits_50',
        #              'mf_nr50':'MaximumFinder_non_robot_edits_50',
        #              'cta50':'ComparisonToAverage_edits_50',
        #              'cta_m50':'ComparisonToAverage_major_edits_50',
        #              'cta_nr50':'ComparisonToAverage_non_robot_edits_50'
        #              }
        doc = xml.dom.minidom.Document()
        style = doc.createProcessingInstruction(
              'xml-stylesheet', 'type="text/xsl" href="change.xsl"')
        doc.appendChild(style)
        changes = doc.createElement('changes')
        title = doc.createElement('thetitle')
        title_string = "Changes in Wikipedia articles at %s" % \
              time.strftime("%A, %d.%m.%Y", time.strptime(self._day, "%Y%m%d"))
        title.appendChild(doc.createTextNode(title_string))
        changes.appendChild(title)
        previous = doc.createElement('navigation')
        previous.setAttribute('name', 'previous')
        previous.appendChild(doc.createTextNode("%s.xml" % yesterday))
        changes.appendChild(previous)
        overview = doc.createElement('navigation')
        overview.setAttribute('name', 'overview')
        overview.appendChild(doc.createTextNode("index.php"))
        changes.appendChild(overview)
        next = doc.createElement('navigation')
        next.setAttribute('name', 'next')
        next.appendChild(doc.createTextNode("%s.xml" % tomorrow))
        changes.appendChild(next)
        #algorithm_list = doc.createElement('algorithmlist')
        #for short, long in algorithms.iteritems():
        #    a = doc.createElement('algorithmname')
        #    a.setAttribute('abbreviation', short)
        #    a.appendChild(doc.createTextNode(long))
        #    algorithm_list.appendChild(a)
        #changes.appendChild(algorithm_list)
        table = doc.createElement('table')
        tableheader = doc.createElement('tableheader')
        for language in self._settings['languages']:
            l = doc.createElement('language')
            l.appendChild(doc.createTextNode(language))
            tableheader.appendChild(l)
        table.appendChild(tableheader)
        for rated_article in self._sorted_articles:
            a = doc.createElement('article')
            topic = doc.createElement('topic')
            for language, title in rated_article.iteritems():
                t = doc.createElement('title')
                t.setAttribute('language', language)
                title = title[0].replace('_', ' ')
                text = doc.createTextNode(title)
                t.appendChild(text)
                topic.appendChild(t)
            a.appendChild(topic)
            for language in self._settings['languages']:
                td = doc.createElement('rating')
                if language in rated_article:
                    if rated_article[language][1] == 'changed':
                        td.appendChild(doc.createElement('detected'))
                    else:
                        td.appendChild(doc.createElement('notdetected'))
                a.appendChild(td)
            table.appendChild(a)
            changes.appendChild(table)
            r = doc.createElement('rating')
            #number = doc.createTextNode(str(article.get_rating()))
            #r.appendChild(number)
            #a.appendChild(r)
        doc.appendChild(changes)
        
        try:
            os.makedirs(self._web_folder)
        except OSError:
            pass
        file_name = self._web_folder + '/' + self._day + '.xml'
        f = file(file_name, 'w')
        f.write(doc.toprettyxml())
        f.close()


def pass_settings(settings):
    global Settings
    Settings = settings

def pass_sql_cursors(sql_cursors):
    """Pass alternative singleton dict of sql cursors to this module.
    The argument must be a singleton which is accessable like a dict and
    contains the key 'auxiliary' and further keys for each language version.
    The values must be oursql cursor objects pointing to the
    desired databases.
    """
    global SQL_Cursors
    SQL_Cursors = sql_cursors


def skipLanguageByReplicationLag():
    yesterday = date.today() - timedelta(1)
    lang_dict = Settings()['languages'][:]

    for language in lang_dict:
        sql_statement = "SELECT UNIX_TIMESTAMP() - UNIX_TIMESTAMP(MAX(rc_timestamp)) FROM recentchanges;"
        SQL_Cursors()[language].execute(sql_statement)
        result = SQL_Cursors()[language].fetchall()
        
        # set update flag if the replication lag isn't too high
        # otherwise remove from languages to be processed
        if result[0][0] < 7200:
            try:
                sql_statement = "INSERT INTO lang_update (lang, day) VALUES ('%s', %s) ON DUPLICATE KEY UPDATE day = %s;" % (language, yesterday.strftime('%Y%m%d'), yesterday.strftime('%Y%m%d'))
                SQL_Cursors()['auxiliary'].execute(sql_statement)
            except oursql.ProgrammingError as e:
                if(e.errno==1146):  # table lang_update does not exist. create it.
                    MyObject()._explain(1, "creating missing table lang_update")
                    SQL_Cursors()['auxiliary'].execute("CREATE TABLE `lang_update` (`day` int(8) unsigned NOT NULL, `lang` varchar(8) NOT NULL, KEY `day` (`day`,`lang`))")
                else:
                    raise
        else:
            Settings()['languages'].remove(language)

if __name__ == '__main__':
    try:
        day = Day(sys.argv[1])
    except IndexError:
        day = Day() - 1
    #global Settings, SQL_Cursors
    #global SQL_Cursors
    #Settings = Settings()
    Settings = Toolserver_Settings
    SQL_Cursors = Toolserver_SQL_Cursors
    skipLanguageByReplicationLag()
    
    #NoticedArticle(day)
    ChangedArticle(day)
    print "processing complete"
