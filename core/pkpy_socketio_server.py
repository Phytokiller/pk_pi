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
    FROM vueJS
"""

# VOLUME (user action)
@sio.on('volume')
def handle_volume(sid, data):
    print("emission du volume")
    sio.emit('/volume', {'volume': data})


# SETTINGS UPDATE
@sio.on('settings')
def handle_settings(sid, data):
    print("paramètres modifiés %s" % data)
    sio.emit('/settings', {'settings': data})


# MANUAL TRAITEMENT (user action)
@sio.on('manual_traitement')
def handle_manual_traitement(sid, data):
    """
        From touch screen app
            'name': 'traitement',
            'value': 'start' OR 'stop'
    """
    print("receive manual traitement %s" % data)
    sio.emit('/manual_traitement', data) 

# IS RUNNING (true/false)
@sio.on('running')
def handle_running(sid, data):
    """
        From touch screen app
            boolean
    """
    print("Running %s" % data)
    sio.emit('/running', data) 


"""
###############################################
#
#    FROM pk_pi
#
################################################
"""
@sio.on('/sensors')
def handle_sensor(sid, data):
    print("receive sensors %s" % data)
    sio.emit('sensors', data) 


@sio.on('/traitement')
def handle_traitement(sid, data):
    print("receive traitement %s" % data)
    sio.emit('traitement', data) 


@sio.on('/counter')
def handle_counter(sid, data):
    print("receive counter %s" % data)
    sio.emit('counter', data) 


@sio.on('/measure')
def handle_counter(sid, data):
    print("receive measure %s" % data)
    sio.emit('measure', data)


@sio.on('/boiler')
def handle_boiler(sid, data):
    print("receive boiler %s " % data)
    sio.emit('boiler', data) 


@sio.on('/door')
def handle_door(sid, data):
    print("receive door %s " % data)
    sio.emit('door', data) 





if __name__ == '__main__':
    eventlet.wsgi.server(eventlet.listen(('', port)), app)
    while True :
        time.sleep(1)
