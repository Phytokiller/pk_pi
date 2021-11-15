import socketio
import eventlet
import time


print (socketio.__version__)
sio = socketio.Server(cors_allowed_origins=[])

app = socketio.WSGIApp(sio, static_files={
    '/': {'content_type': 'text/html', 'filename': 'index.html'}
})

port = 5000

@sio.event
def connect(sid, environ):
    print('connect ', sid)
@sio.event
def disconnect(sid):
    print('disconnect ', sid)


"""
###############################################
#
#    FROM MANAGER (WEB)
#
################################################
"""
@sio.on('syncFromManager')
def handle_processing(sid, data):
    # Return elapsed_time and palettes object while bath is processing
    print("Syncing data from manager %s" % data)
    sio.emit('/syncFromManager', data, broadcast=True, include_self=False)

"""
###############################################
#
#    FROM SCREEN (PI)
#
################################################
"""
@sio.on('syncFromDevice')
def handle_processing(sid, data):
    # Return elapsed_time and palettes object while bath is processing
    print("Syncing data from device %s" % data)
    sio.emit('/syncFromDevice', data, broadcast=True, include_self=False)

@sio.on('processing')
def handle_processing(sid, data):
    # Return elapsed_time and palettes object while bath is processing
    print("Bath processing data %s" % data)
    sio.emit('/processing', data, broadcast=True, include_self=False)

@sio.on('start')
def handle_processing(sid, data):
    # Return true each time a bath is starting
    print("Bath started %s" % data)
    sio.emit('/start', data, broadcast=True, include_self=False)

@sio.on('stop')
def handle_processing(sid, data):
    # Return true each time a bath was done
    print("Bath stopped %s" % data)
    sio.emit('/stop', data, broadcast=True, include_self=False)

@sio.on('alarm')
def handle_processing(sid, data):
    # Return true if an error has been detected while processing bath
    print("Alarm %s" % data)
    sio.emit('/alarm', data, broadcast=True, include_self=False)


"""
###############################################
#
#    FROM ARDUINO
#
################################################
"""
@sio.on('/sensors')
def handle_sensor(sid, data):
    print("receive sensors %s" % data)
    sio.emit('sensors', data) 


@sio.on('/boiler')
def handle_boiler(sid, data):
    print("receive boiler %s " % data)
    sio.emit('boiler', data) 


@sio.on('/door')
def handle_door(sid, data):
    print("receive door %s " % data)
    sio.emit('door', data) 

########### NOT IMPLEMENTED YET ###################
@sio.on('/t1offset_ard')
def handle_t1offset_ard(sid, data):
    print("receive t1offset from ardbox %s " % data)
    sio.emit('t1offset_ard', data) 

@sio.on('/t2offset_ard')
def handle_t2offset_ard(sid, data):
    print("receive t2offset from ardbox %s " % data)
    sio.emit('t2offset_ard', data) 

@sio.on('/tboiler_ard')
def handle_tboiler_ard(sid, data):
    print("receive tboiler from ardbox %s " % data)
    sio.emit('tboiler_ard', data) 





if __name__ == '__main__':
    eventlet.wsgi.server(eventlet.listen(('', port)), app)
    while True :
        time.sleep(1)
