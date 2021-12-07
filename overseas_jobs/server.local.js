let express = require("express"),
app = express(),
https = require("http").createServer(app),
io = require("socket.io")(https),
nicknames = [];

io.sockets.on("connection", (socket) => {
  socket.on('join', function(data){
	console.log('connected');
    if(nicknames.indexOf(data) != -1){
      updateNicknames();
    }else{
      socket.nickname = data;
      nicknames.push(socket.nickname);
      updateNicknames();
    }
  });
  function updateNicknames(){
    io.emit('usernames', nicknames);
  }

  socket.on("chat-message", (data) => {
	socket.broadcast.emit("channel-chat", data);
	socket.broadcast.emit("count-message", {
		scoutid_or_applyid: data.scoutid_or_applyid,
		type: data.type
	});
  });

  socket.on("deactived",(data) => {
    io.emit('block-user', data);
  });

//   socket.on('checkSessionTimeOut',() => {
//       io.emit('sessionTimeOut');
//   })

  socket.on("typing", (data) => {
    socket.broadcast.emit("usertyping", data);
  }); 

  socket.on("remove-message", (data) => {
    socket.broadcast.emit("message-removed", data);
  });

  /**
   * Message read event
   * @param {Object} data message data passed from broadcasting entity
   * @param {number} data.scoutid_or_applyid Scout ID or Apply ID of message
   * @param {string} data.type Type of message (scout, apply, sponsor)
   * @param {Array} data.message_ids Array of message ids that are read
   * @param {string} data.read_time message read time
   */
  socket.on("message-read", (data) => {
    socket.broadcast.emit("message-been-read",data);
  });

  socket.on("disconnect", () => {
    console.log('Disconnected');
    if(!socket.nickname) return;
    nicknames.splice(nicknames.indexOf(socket.nickname),1);
    updateNicknames();
  });

});

https.listen(4001, () => {
  console.log("Server is started !!!");
});