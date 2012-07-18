#!/opt/ts/python/2.7/bin/python
# is 'select X... group by X' really faster than 'select distinct X'?

import sys
import time
import oursql

if __name__ == '__main__':
    connection= oursql.connect(read_default_file='~/.my.cnf', use_unicode=False)
    cursor= oursql.Cursor(connection)
    cursor.execute("USE u_jkroll_changedetector_mt_u")
    
    timedata= ( 
    {
        'query': "SELECT DISTINCT day FROM changed_article",
        'mintime': 10000000,
        'maxtime': 0,
        'totaltime': 0
    },
    {
        'query': "SELECT day FROM changed_article GROUP BY day",
        'mintime': 10000000,
        'maxtime': 0,
        'totaltime': 0
    } )
    
    numruns= 10
    
    for i in range(numruns):
        for k in timedata:
            start= time.time()
            cursor.execute(k['query'])
            cursor.fetchall()
            delta= time.time()-start
            if delta < k['mintime']: k['mintime']= delta
            if delta > k['maxtime']: k['maxtime']= delta
            k['totaltime']+= delta
        sys.stdout.write('.')
        sys.stdout.flush()
    
    print
    
    for t in timedata:
        print 'query: %s' % t['query']
        print 'mintime: %.2f' % t['mintime']
        print 'maxtime: %.2f' % t['maxtime']
        print 'avg:     %.2f' % (t['totaltime']/numruns)

