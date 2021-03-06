import socketio
import time
import random
#from pynput import keyboard

s_io = socketio.Client()
Tmin = 49.00
Tmax = 51.00
Tdiff = 1

# to change state of boiler or door with keyboard
boilerKey = 'b'
doorKey = 'd'

boilerState = False
doorState = False
oldBoilerState = False
oldDoorState = False

now = time.time()

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

'''
#Listen keyboard
def on_press(key):
    try:
        print('alphanumeric key {0} pressed'.format(
            key.char))
    except AttributeError:
        print('special key {0} pressed'.format(
            key))
listener = keyboard.Listener(
    on_press=on_press,
    on_release=on_release)
listener.start()
'''

if __name__ == "__main__":
    print("start")

    # CONNECT websocket Client

    while not s_io.connected:
        print("CONNECT")
        try_socket_connection(s_io)
        time.sleep(0.5)

    while True :

        # SEND DATA
        T1 = round(random.uniform(Tmin, Tmax), 2)
        diff = random.uniform(-Tdiff/2,+Tdiff/2)
        T2 = round(T1+diff,2)
        now = time.time()
        s_io.emit('/sensors', {
                'T1': T1,
                'T2': T2
            })

        if oldBoilerState != boilerState :
            s_io.emit('/boiler', {
                'boiler': boilerState
            })
            oldBoilerState = boilerState
            print("change boiler")
        if oldDoorState != doorState :
            s_io.emit('/door', {
                'door': doorState
            })
            oldDoorState = doorState
            print("change door")

            
        time.sleep(3)

            