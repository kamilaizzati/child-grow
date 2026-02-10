<div>
  <style>
    #main-content {
  margin: auto;
  width: 800px;
  border: 3px solid rgb(216, 216, 216);
  padding: 10px;
}

input{
    min-width: 400px;
}

#text-interface{
    display: none;
}
  </style>
    <script src="resources/views/livewire/web-api.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/makeabilitylab/p5js/_libraries/serial.js"></script>
    <button id="connect-button" onclick="onConnectButtonClick()">Connect via Serial Port</button>
    <div id="data"></div>


    <div id="main-content">
      <button id="connect-button" onclick="onButtonConnectToSerialDevice()">
        Connect via Serial Port
      </button>
      <div id="text-interface">
        <h3>Enter text:</h3>
        <input placeholder="Enter some text" name="input" />
  
        <h3>Display text:</h3>
        <p id="output-text"></p>
  
        <h3>Received from Arduino:</h3>
        <p id="received-text"></p>
      </div>
    </div>

    <button id="button">con</button>

<br>
    <button id="send">send to arduino</button>
    <br>

    <button id="getdata">get data</button>

    <script>
var databaru = '';
var tempdatabaru = '';

let counter = 0;


document.querySelector('#button').addEventListener('click', async () => {

  requestSerialPort();
    
    // console.log(databaru);

     // Output: Data received from Arduino
  
  console.log(port);

});
async function requestSerialPort() {
  const port = await navigator.serial.requestPort();
  await port.open({ baudRate: 9600 }); // Set the baud rate to match the Arduino code

  return port;
}
async function sendData(data) {
  const encoder = new TextEncoder();
  const writer = port.writable.getWriter();
  await writer.write(encoder.encode(data));
  writer.releaseLock();
}


document.querySelector('#send').addEventListener('click', async () => {
  if (serial.isOpen()) {
      // console.log("Writing to serial: ", "hehe");
      // serial.writeLine(1);
      serial.write(1);
    }
});

document.querySelector('#getdata').addEventListener('click', async () => {
  const reader = port.readable.getReader();

  while (true) {

      const { value, done } = await reader.read();
      if (done) break;
      const textDecoder = new TextDecoder();
      data = textDecoder.decode(value);
        databaru += data;
      console.log(databaru);
      // counter++;
      // if (counter === 15) {
      //   break;
      // }
    }
});

        let dataku = '';
        document.getElementById("data").innerHTML = "hehhe";
</script>





<script>
   const rcvdText = document.getElementById('received-text');


const inputText = document.querySelector('input');
  const outputText = document.getElementById('output-text');

  inputText.addEventListener('input', updateOutputText);

    // And update the textContent of 'received-text' when new data is received
    function onSerialDataReceived(eventSender, newData) {
    console.log("onSerialDataReceived", newData);
    rcvdText.textContent = newData;
  }

  // Called automatically when the input textbox is updated
  function updateOutputText(e) {
    outputText.textContent = e.target.value;
    serialWriteTextData(e.target.value);
  }

  // Send text data over serial
  async function serialWriteTextData(textData) {
    if (serial.isOpen()) {
      console.log("Writing to serial: ", textData);
      serial.writeLine(textData);
    }
  }

  // Setup Web Serial using serial.js
  const serial = new Serial();
  serial.on(SerialEvents.CONNECTION_OPENED, onSerialConnectionOpened);
  serial.on(SerialEvents.CONNECTION_CLOSED, onSerialConnectionClosed);
  serial.on(SerialEvents.DATA_RECEIVED, onSerialDataReceived);
  serial.on(SerialEvents.ERROR_OCCURRED, onSerialErrorOccurred);

  async function onButtonConnectToSerialDevice() {
    console.log("onButtonConnectToSerialDevice");
    if (!serial.isOpen()) {
      await serial.connectAndOpen();
    }
  }

  function onSerialErrorOccurred(eventSender, error) {
    console.log("onSerialErrorOccurred", error);
  }

  function onSerialConnectionOpened(eventSender) {
    console.log("onSerialConnectionOpened", eventSender);
    document.getElementById("connect-button").style.display = "none";
  document.getElementById("text-interface").style.display = "block";
  }

  function onSerialConnectionClosed(eventSender) {
    console.log("onSerialConnectionClosed", eventSender);
  }

  function onSerialDataReceived(eventSender, newData) {
    console.log("onSerialDataReceived", newData);
  }

  async function onConnectButtonClick() {
    console.log("Connect button clicked!");
  }
</script>
</div>