import socketio
import time
import random
import serial

s_io = socketio.Client()

# Open Serial
ser = serial.Serial(port='/dev/ttyAMA3', baudrate=38400)

# Try socket connection
def try_socket_connection(socket_io_client: socketio.Client):
    try:
        socket_io_client.connect(
            "http://localhost:5000")
    except Exception as e:
        print("Could no connect to SocketIO server at instanciation, retrying later, ", e)
    else:
        print("PKPY connected socket server with SID %s and transport %s" %
            (socket_io_client.sid, socket_io_client.transport))


##############
# Send message RS-485
##############
def sendSerial(message) :
    byte_to_send = bytes(message, 'utf-8')
    ser.write(byte_to_send)

@s_io.on('/alarm')
def handle_alarm(sid, data):
    print("receive alarm %s" % data)
    if (data == 1) :
        sendSerial('!alarm:1\n')
    else :
        sendSerial('!alarm:0\n')

@s_io.on('/T1offset')
def handle_t1offset(sid, data):
    print("receive t1 offset %s" % data)
    sendSerial('!T1offset:%s\n' %data)

@s_io.on('/T2offset')
def handle_t2offset(sid, data):
    print("receive t2 offset %s" % data)
    sendSerial('!T2offset:%s\n' %data)


if __name__ == "__main__":
    print("start")
    
    try :
        ser.open()
    except :
        print("Serial port already open")

    # CONNECT websocket Client
    while not s_io.connected:
        print("CONNECT")
        try_socket_connection(s_io)
        time.sleep(0.5)

    while True :
        #listen Serial and send to socketserver
        line = ser.readline()
        line = line.decode("utf-8")
        line = line[:-1] # remove '\n' at the end
        if line.startswith('sensors') :
            T1 = line.split(':')[1]
            T2 = line.split(':')[2]
            s_io.emit('/sensors', {
                'T1': T1,
                'T2': T2
            })
        elif line.startswith('boiler') :
            boilerRcv = line.split(':')[1]
            boilerState = False
            if boilerRcv == "1" :
                boilerState = True
            print("EMIT : boiler:%s" % boilerState)
            s_io.emit('/boiler', {
                'boiler': boilerState
            })
        elif line.startswith('door') :
            doorRcv = line.split(':')[1]
            doorState = False
            print(bytes(doorRcv, 'utf-8'))
            if doorRcv == "1" :
                doorState = True
            print("EMIT : door:%s" % doorState)
            s_io.emit('/door', {
                'door': doorState
            })


            