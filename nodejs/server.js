
var app = require('http').createServer(handler),
    io = require('socket.io').listen(app),
    fs = require('fs'),
    mysql = require('mysql'),
    connectionsArray = [],
    connection = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'manthanodb',
        port: 3306
    }),
    POLLING_INTERVAL = 3000,
    pollingTimer;

// If there is an error connecting to the database
connection.connect(function(err) {
    // connected! (unless `err` is set)
    console.log(err);
});

// creating the server ( localhost:8000 )
app.listen(8000);

// on server started we can load our client.html page
function handler(req, res) {

}

/*
 *
 * HERE IT IS THE COOL PART
 * This function loops on itself since there are sockets connected to the page
 * sending the result of the database query after a constant interval
 *
 */
var number_of_notfications = 0;
var number = 0;
var pollingLoop = function() {

    // Doing the database query
    var query = connection.query('SELECT * FROM notification order by time desc'),
        result = []; // this array will contain the result of our db query



    // setting the query listeners
    query
        .on('error', function(err) {
            // Handle error, and 'end' event will be emitted after this as well
            console.log(err);
            updateSockets(err);
        })
        .on('result', function(res) {
            // it fills our array looping on each user row inside the db
            result.push(res);
        })
        .on('end', function() {
            // loop on itself only if there are sockets still connected
            if (connectionsArray.length) {
                pollingTimer = setTimeout(pollingLoop, POLLING_INTERVAL);
                if(number_of_notfications != result.length){
                    number = result.length - number_of_notfications;
                    number_of_notifications = result.length;
                }
                else{

                }
                updateSockets({
                    number: number,
                    users: result
                });
            }
        });

};


// creating a new websocket to keep the content updated without any AJAX request
io.sockets.on('connection', function(socket) {

    console.log('Number of connections:' + connectionsArray.length);
    // starting the loop only if at least there is one user connected
    if (!connectionsArray.length) {
        pollingLoop();
    }

    socket.on('disconnect', function() {
        var socketIndex = connectionsArray.indexOf(socket);
        console.log('socket = ' + socketIndex + ' disconnected');
        if (socketIndex >= 0) {
            connectionsArray.splice(socketIndex, 1);
        }
    });

    console.log('A new socket is connected!');
    connectionsArray.push(socket);

});

var updateSockets = function(data) {
    // adding the time of the last update
    data.time = new Date();
    // sending new data to all the sockets connected
    connectionsArray.forEach(function(tmpSocket) {
        tmpSocket.volatile.emit('notification', data);
    });
};