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

sio.on('getSettings')
def handle_processing(sid, data):
    print("getSettings %s" % data)
    sio.emit('/getSettings', data, broadcast=True, include_self=False)

sio.on('setSettings')
def handle_processing(sid, data):
    # Return : { "id": 1, "account_id": "2", "user_id": "c332dc73-8110-4a45-b510-e54ea4b2b8b5", "device_id": null, "manager_url": null, "offset_t1": 0, "offset_t2": 0, "created_at": "2021-11-15T10:35:11.000000Z", "updated_at": "2021-11-15T11:18:06.000000Z" } 
    print("setSettings %s" % data)
    sio.emit('/setSettings', data, broadcast=True, include_self=False)



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

@sio.on('/settings')
def handle_settings(sid, data):
    # {T1offset:xx.xx, T2offset:xx.xx, Tboiler:xx.xx}
    print("receive settings %s " % data)
    sio.emit('settings', data) 




if __name__ == '__main__':
    eventlet.wsgi.server(eventlet.listen(('', port)), app)
    while True :
        time.sleep(1)
