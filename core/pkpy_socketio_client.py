import socketio
import time
import random
import serial

s_io = socketio.Client()
Tmin = 49.00
Tmax = 51.00
Tdiff = 1


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

@sio.on('/alarm')
def handle_alarm(sid, data):
    print("receive alarm %s" % data)
    if (data == 1) :
        sendSerial('%alarm:1\n')
    else :
        sendSerial('%alarm:0\n')




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
        if line.startswith('sensor') :
            T1 = line.split(':')[1]
            T2 = line.split(':')[2]
            s_io.emit('/sensors', {
                'T1': T1,
                'T2': T2
            })
        elif line.startswith('boiler') :
            boilerState = line.split(':')[1]
            s_io.emit('/boiler', {
                'boiler': boilerState
            })
        elif line.startswith('door') :
            boilerState = line.split(':')[1]
            s_io.emit('/boiler', {
                'boiler': boilerState
            })


            