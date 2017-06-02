
# strace
trace system calls
strace -o /tmp/strace.log php -v
-> get the order in which the dependencies are loaded: 
`strace -e open -o /tmp/strace.log php -v`

know which .ini files php is reading
cat /tmp/strace.log | grep ini

# lsof
everything is a filepointer
lsof -p 17828 (processid) | grep IP

list open sockets: find which process is using port 9000
lsof -i -TCP:9000

# gdb
like Xdebug but for C probrems (Core dumped issues, segmentation fault, hardware interrupts, low level)
gdb --args php filename.php

(gdb) bt full (backtrace)
