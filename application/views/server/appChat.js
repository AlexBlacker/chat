var PORT = 8008;

var options = {
   'log level': 0
};

var express = require('express');
var app = express();
var http = require('http');
var server = http.createServer(app);
var io = require('socket.io').listen(server, options);
server.listen(PORT);

//app.use('/static', express.static(__dirname + '/static'));

app.get('/', function (req, res) {
    res.sendfile(__dirname + '/chat.php');
});

io.sockets.on('connection', function (client) {
	
    client.on('message', function (message) {
        try {
            client.emit('message', message);
            client.broadcast.emit('message', message);
        } catch (e) {
            console.log(e);
            client.disconnect();
        }
    });
    
    client.on('type', function (name) {
        try {
            client.emit('type', name);
            client.broadcast.emit('type', name);
        } catch (e) {
            console.log(e);
            client.disconnect();
        }
    });
    
    client.on('read', function (mess) {
        try {
            client.emit('read', mess);
            client.broadcast.emit('read', mess);
        } catch (e) {
            console.log(e);
            client.disconnect();
        }
    });
    
});