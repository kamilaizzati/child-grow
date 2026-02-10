{{-- serial --}}
<script src="https://cdn.jsdelivr.net/gh/makeabilitylab/p5js/_libraries/serial.js"></script>

<div class="py-5">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
      <x-jet-button class="ml-4" id="connect-button" onclick="onButtonConnectToSerialDevice()">
        Connect Device
      </x-jet-button>
      <x-jet-button id="send-data " class="hidden">Tambah Data</x-jet-button>


      <script>
        var dataku = '';
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
        document.getElementById("send-data").style.display = "block";
        }
      
        function onSerialConnectionClosed(eventSender) {
          console.log("onSerialConnectionClosed", eventSender);
        }
      
        function onSerialDataReceived(eventSender, newData) {
          console.log("onSerialDataReceived", newData);
          dataku = newData;
      
          let lingkar = newData.slice(1 ,5);
          let panjang = newData.slice(6,10);
          let berat = newData.slice(11,15);
          console.log(lingkar, " lingkar,");
          console.log(panjang, " panjang,");
          console.log(berat, " berat,");

          var child = <?=json_encode($childrenID)?>;
            window.location=('http://127.0.0.1:8000/show/'+child+'/'+lingkar+'/'+panjang+'/'+berat+'');
          console.log(window.location);
        }
      
        async function onConnectButtonClick() {
          console.log("Connect button clicked!");
        }
        
        document.querySelector('#send-data').addEventListener('click', async () => {
        if (serial.isOpen()) {
          // console.log("Writing to serial: ", "hehe");
          // serial.writeLine(1);
            serial.write(1);
            console.log("dataku ", dataku);
          }    
        });
      </script>

  <div class="flex flex-wrap block justify-content-center">
    <div class="w-full block md:w-1/2">
      <canvas id="myChart"></canvas>
    </div>
    <div class="w-full block md:w-1/2">
      <canvas id="myChart1"></canvas>
    </div>
  </div>
    @php
    // dipecah '2023/06/15'
    $dateTahun = \Carbon\Carbon::parse($birth)->diff(\Carbon\Carbon::now())->format('%y');            
    $dateBulan = \Carbon\Carbon::parse($birth)->diff(\Carbon\Carbon::now())->format('%m');
    $dateHari = \Carbon\Carbon::parse($birth)->diff(\Carbon\Carbon::now())->format('%d');
    
    if ($dateTahun == 0 && $dateBulan != 0) { 
      $tglLahir = $dateBulan . ' Bulan, '.$dateHari.' Hari'; 
    }elseif ($dateTahun == 0 && $dateBulan == 0) { 
      $tglLahir = $dateHari.' Hari'; 
    }elseif ($dateBulan == 0) { 
      $tglLahir = $dateTahun . ' Tahun,'.$dateHari.' Hari'; 
    }elseif ($dateHari == 0 && $dateBulan == 0) {
      $tglLahir = $dateTahun . ' Tahun'; 
    }else { 
      $tglLahir = $dateTahun . ' Tahun, '.$dateBulan .' Bulan, '.$dateHari.' Hari'; } 
    @endphp
    

    <?php 
        
    $dataku = ''; 
    $temp_lingkar = [];
    $temp_length = [];
    $temp_weight = [];

    ?>
        
        @foreach ($history as $item)
          
          <?php
          $i = 0;
          $temp_lingkar[$loop->index] = $item->lingkar;
          $temp_length[$loop->index] = $item->length;
          $temp_weight[$loop->index] = $item->weight;

          ?>
        @endforeach
            
            <div class="sm:flex block px-2">

                <table class="table-fixed hover w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 w-20">No.</th>
                            <th class="px-4 py-2">Lingkar (cm)</th>
                            <th class="px-4 py-2">Tinggi (cm)</th>
                            <th class="px-4 py-2">Berat(kg)</th>
                            <th class="px-4 py-2">BMI</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $item)

                        <?php
                        $statusWeight = "";
                        $statusHeight = "";
                        $getThn = \Carbon\Carbon::parse($item->created_at)->diff($birth)->format('%y');            
                        $getBln = \Carbon\Carbon::parse($item->created_at)->diff($birth)->format('%m');
                        $getHari = \Carbon\Carbon::parse($item->created_at)->diff($birth)->format('%d');

                        $getBln = ($getThn*12) + $getBln;
                        $newLength = $item->length * 0.01;
                        $getBmi = $item->weight / ($newLength * $newLength); 

                        if ($gender == "Perempuan") {
                          if ($getBln == 49) {
                            if ($item->weight >= 14.2 && $item->weight <= 18.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 18.8) {
                              $statusWeight = "over";
                            }

                          }else if ($getBln == 50) {
                            if ($item->weight >= 14.3 && $item->weight <= 19.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 51) {
                            if ($item->weight >= 14.5 && $item->weight <= 19.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 52) {
                            if ($item->weight >= 14.6 && $item->weight <= 19.4) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.4) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 53) {
                            if ($item->weight >= 14.8 && $item->weight <= 19.7) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.7) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 54) {
                            if ($item->weight >= 14.9 && $item->weight <= 19.9) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.9) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 55) {
                            if ($item->weight >= 15.1 && $item->weight <= 20.1) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.1) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.1) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 56) {
                            if ($item->weight >= 15.2 && $item->weight <= 20.3) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.3) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 57) {
                            if ($item->weight >= 15.3 && $item->weight <= 20.6) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.6) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 58) {
                            if ($item->weight >= 15.5 && $item->weight <= 20.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 59) {
                            if ($item->weight >= 15.6 && $item->weight <= 21.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 60) {
                            if ($item->weight >= 15.8 && $item->weight <= 21.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 61) {
                            if ($item->weight >= 15.9 && $item->weight <= 21.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 62) {
                            if ($item->weight >= 16.0 && $item->weight <= 21.4) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.0) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.4) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 63) {
                            if ($item->weight >= 16.2 && $item->weight <= 21.6) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.6) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 64) {
                            if ($item->weight >= 16.3 && $item->weight <= 21.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 65) {
                            if ($item->weight >= 16.5 && $item->weight <= 22.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 66) {
                            if ($item->weight >= 16.6 && $item->weight <= 22.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 67) {
                            if ($item->weight >= 16.8 && $item->weight <= 22.5) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.5) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 68) {
                            if ($item->weight >= 16.9 && $item->weight <= 22.7) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.7) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 69) {
                            if ($item->weight >= 17 && $item->weight <= 22.9) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.9) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 70) {
                            if ($item->weight >= 17.2 && $item->weight <= 23.1) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.1) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 71) {
                            if ($item->weight >= 17.3 && $item->weight <= 23.3) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.3) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 72) {
                            if ($item->weight >= 17.5 && $item->weight <= 23.5) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.5) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 73) {
                            if ($item->weight >= 17.6 && $item->weight <= 23.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 74) {
                            if ($item->weight >= 17.8 && $item->weight <= 24.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 24.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 75) {
                            if ($item->weight >= 17.9 && $item->weight <= 24.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 24.2) {
                              $statusWeight = "over";
                            }
                          }

                          // TINGGI PEREMPUAN
                          if ($getBln == 49) {
                            if ($item->length >= 99 && $item->length <= 107.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 99) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 107.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 50) {
                            if ($item->length >= 99.5 && $item->length <= 108.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 99.5) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 108.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 51) {
                            if ($item->length >= 100.1 && $item->length <= 108.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 100.1) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 108.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 52) {
                            if ($item->length >= 100.6 && $item->length <= 109.5) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 100.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 109.5) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 53) {
                            if ($item->length >= 101.1 && $item->length <= 110.1) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 101.1) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 110.1) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 54) {
                            if ($item->length >= 101.6 && $item->length <= 110.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 101.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 110.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 55) {
                            if ($item->length >= 102.3 && $item->length <= 111.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 102.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 111.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 56) {
                            if ($item->length >= 102.7 && $item->length <= 111.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 102.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 111.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 57) {
                            if ($item->length >= 103.2 && $item->length <= 112.5) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 103.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 112.5) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 58) {
                            if ($item->length >= 103.7 && $item->length <= 113) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 103.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 113) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 59) {
                            if ($item->length >= 104.2 && $item->length <= 113.6) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 104.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 113.6) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 60) {
                            if ($item->length >= 104.7 && $item->length <= 114.2) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 104.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.2) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 61) {
                            if ($item->length >= 104.8 && $item->length <= 114.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 104.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.4) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 62) {
                            if ($item->length >= 105.3 && $item->length <= 114.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 105.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 63) {
                            if ($item->length >= 105.8 && $item->length <= 115.5) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 105.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 115.5) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 64) {
                            if ($item->length >= 106.8 && $item->length <= 116) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 106.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 116) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 65) {
                            if ($item->length >= 106.8 && $item->length <= 116.6) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 106.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 116.6) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 66) {
                            if ($item->length >= 107.2 && $item->length <= 117.1) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 107.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 117.1) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 67) {
                            if ($item->length >= 107.7 && $item->length <= 117.6) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 107.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 117.6) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 68) {
                            if ($item->length >= 108.2 && $item->length <= 118.2) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 108.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 118.2) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 69) {
                            if ($item->length >= 108.6 && $item->length <= 118.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 108.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 118.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 70) {
                            if ($item->length >= 109.1 && $item->length <= 119.2) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 109.1) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 119.2) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 71) {
                            if ($item->length >= 109.6 && $item->length <= 119.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 109.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 119.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 72) {
                            if ($item->length >= 110 && $item->length <= 120.2) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 110) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 120.2) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 73) {
                            if ($item->length >= 110.5 && $item->length <= 120.8) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 110.5) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 120.8) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 74) {
                            if ($item->length >= 110.9 && $item->length <= 121.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 110.9) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 121.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 75) {
                            if ($item->length >= 111.3 && $item->length <= 121.8) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 111.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 121.8) {
                              $statusHeight = "over";
                            }
                          }

                        
                        }
                        
                        if ($gender == "Laki-Laki") {
                          // BERAT LAKI-LAKI
                          if ($getBln == 49) {
                            if ($item->weight >= 14.5 && $item->weight <= 18.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 18.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 50) {
                            if ($item->weight >= 14.7 && $item->weight <= 19.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.7) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 51) {
                            if ($item->weight >= 14.8 && $item->weight <= 19.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 14.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 52) {
                            if ($item->weight >= 15 && $item->weight <= 19.4) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.4) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 53) {
                            if ($item->weight >= 15.1 && $item->weight <= 19.6) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.1) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.6) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 54) {
                            if ($item->weight >= 15.2 && $item->weight <= 19.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 19.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 55) {
                            if ($item->weight >= 15.4 && $item->weight <= 20) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.4) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 56) {
                            if ($item->weight >= 15.5 && $item->weight <= 20.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 57) {
                            if ($item->weight >= 15.6 && $item->weight <= 20.4) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.4) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 58) {
                            if ($item->weight >= 15.8 && $item->weight <= 20.6) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.6) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 59) {
                            if ($item->weight >= 15.9 && $item->weight <= 20.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 15.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 20.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 60) {
                            if ($item->weight >= 16 && $item->weight <= 21) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 61) {
                            if ($item->weight >= 16.3 && $item->weight <= 21.1) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.1) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 62) {
                            if ($item->weight >= 16.4 && $item->weight <= 21.3) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.4) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.3) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 63) {
                            if ($item->weight >= 16.6 && $item->weight <= 21.5) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.6) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.5) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 64) {
                            if ($item->weight >= 16.7 && $item->weight <= 21.7) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.7) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 21.7) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 65) {
                            if ($item->weight >= 16.9 && $item->weight <= 22.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 16.9) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 66) {
                            if ($item->weight >= 17 && $item->weight <= 22.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.2) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 67) {
                            if ($item->weight >= 17.2 && $item->weight <= 22.4) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.4) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 68) {
                            if ($item->weight >= 17.4 && $item->weight <= 22.6) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.4) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.6) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 69) {
                            if ($item->weight >= 17.5 && $item->weight <= 22.8) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 22.8) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 70) {
                            if ($item->weight >= 17.7 && $item->weight <= 23.1) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.7) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.1) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 71) {
                            if ($item->weight >= 17.8 && $item->weight <= 23.3) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 17.8) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.3) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 72) {
                            if ($item->weight >= 18 && $item->weight <= 23.5) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 18) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.5) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 73) {
                            if ($item->weight >= 18.2 && $item->weight <= 23.7) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 18.2) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 23.7) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 74) {
                            if ($item->weight >= 18.3 && $item->weight <= 24.0) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 18.3) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 24.0) {
                              $statusWeight = "over";
                            }
                          }else if ($getBln == 75) {
                            if ($item->weight >= 18.5 && $item->weight <= 24.2) {
                              $statusWeight = "normal";
                            }elseif ($item->weight <= 18.5) {
                              $statusWeight = "kurang";
                            }elseif ($item->weight >= 24.2) {
                              $statusWeight = "over";
                            }
                          }

                          // TINGGI LAKI_LAKI
                          if ($getBln == 49) {
                            if ($item->length >= 99.7 && $item->length <= 108.1) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 99.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 108.1) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 50) {
                            if ($item->length >= 100.2 && $item->length <= 108.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 100.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 108.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 51) {
                            if ($item->length >= 100.7 && $item->length <= 109.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 100.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 109.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 52) {
                            if ($item->length >= 101.2 && $item->length <= 109.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 101.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 109.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 53) {
                            if ($item->length >= 101.7 && $item->length <= 110.5) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 101.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 110.5) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 54) {
                            if ($item->length >= 102.3 && $item->length <= 111.1) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 102.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 111.1) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 55) {
                            if ($item->length >= 102.8 && $item->length <= 111.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 102.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 111.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 56) {
                            if ($item->length >= 103.3 && $item->length <= 112.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 103.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 112.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 57) {
                            if ($item->length >= 103.8 && $item->length <= 112.8) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 103.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 112.8) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 58) {
                            if ($item->length >= 104.3 && $item->length <= 113.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 104.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 113.4) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 59) {
                            if ($item->length >= 104.8 && $item->length <= 114.0) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 104.8) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.0) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 60) {
                            if ($item->length >= 105.3 && $item->length <= 114.6) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 105.3) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.6) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 61) {
                            if ($item->length >= 105.7 && $item->length <= 114.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 105.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 114.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 62) {
                            if ($item->length >= 106.2 && $item->length <= 115.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 106.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 115.4) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 63) {
                            if ($item->length >= 106.7 && $item->length <= 116) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 106.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 116) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 64) {
                            if ($item->length >= 107.2 && $item->length <= 116.5) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 107.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 116.5) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 65) {
                            if ($item->length >= 107.7 && $item->length <= 117.1) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 107.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 117.1) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 66) {
                            if ($item->length >= 108.2 && $item->length <= 117.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 108.2) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 117.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 67) {
                            if ($item->length >= 108.7 && $item->length <= 118.2) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 108.7) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 118.2) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 68) {
                            if ($item->length >= 109.1 && $item->length <= 118.7) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 109.1) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 118.7) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 69) {
                            if ($item->length >= 109.6 && $item->length <= 119.3) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 109.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 119.3) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 70) {
                            if ($item->length >= 110.1 && $item->length <= 119.8) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 110.1) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 119.8) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 71) {
                            if ($item->length >= 110.6 && $item->length <= 120.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 110.6) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 120.4) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 72) {
                            if ($item->length >= 111 && $item->length <=120.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 111) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 120.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 73) {
                            if ($item->length >= 111.5 && $item->length <= 121.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 111.5) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 121.4) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 74) {
                            if ($item->length >= 111.9 && $item->length <= 121.9) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 111.9) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 121.9) {
                              $statusHeight = "over";
                            }
                          }else if ($getBln == 75) {
                            if ($item->length >= 112.4 && $item->length <= 122.4) {
                              $statusHeight = "normal";
                            }elseif ($item->length <= 112.4) {
                              $statusHeight = "kurang";
                            }elseif ($item->length >= 122.4) {
                              $statusHeight = "over";
                            }
                          }


                        }

                        ?>
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{$item->lingkar}}</td>
                            <td class="border px-4 py-2">{{$item->length}}</td>
                            <td class="border px-4 py-2">{{$item->weight}}</td>
                            <td class="border px-4 py-2">{{number_format($getBmi, 2)}}</td>
                            <td class="border px-4 py-2">
                              @if ($statusWeight == "normal" && $statusHeight == "normal")
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Normal</span>
                              
                              @elseif ($statusWeight == "over")
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">OverWeight</span>
                              @elseif ($statusWeight == "kurang")
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">UnderWeight</span>
                              @else ($statusWeight == "normal")
                              <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-gray-300">Weight normal</span>
                              @endif

                              @if ($statusHeight == "kurang")
                              <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Stunting</span>
                              @endif
                        
                            </td>
                            <td class="border px-4 py-2">{{ $item->created_at }}</td>

                            </tr>
                            @empty
                            <tr>
                                <td class="border px-4 py-2 text-center text-md font-bold" colspan="5">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <script
        src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
    <script>
		new Chart(document.getElementById("myChart"), {
			type : 'line',
			data : {
				labels : <?php echo json_encode($temp_weight) ?>,
				datasets : [
                    {
                        data : <?php echo json_encode($temp_length) ?>,
                        label : "Tinggi & Berat",
                        borderColor : "#0E8388",
                        fill : false
                    } ]
			},
        options : {
          responsive: true,
          scales: {
            y: {
              data : ["January","February","March","April","May","June","July"],
              title : {
              display : true,
              text : 'Panjang (CM)'
              },
              type: 'linear',
              display: true,
              position: 'left',
            },
            x:{
              title : {
                display : true,
                text : 'Berat (KG)'
                },
            }
          }
			}
		});

    new Chart(document.getElementById("myChart1"), {
			type : 'line',
			data : {
				labels : [1,2,3,4,5,6,7,8,9,10,11,12],
				datasets : [
                    {
                        data : <?php echo json_encode($temp_lingkar) ?>,
                        label : "Lingkar",
                        borderColor : "#0E8388",
                        fill : false
                    } 
          
          ]
			},
			options : {
                responsive: true,
                scales: {
                    y: {
                        data : ["January","February","March","April","May","June","July"],
                        title : {
                        display : true,
                        text : 'Lingkar (CM)'
                        },
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    x:{
                      title : {
                      display : true,
                      text : 'Pengukuran'
                      },
                  }
                }
			}
		});
	</script>

    <script src="{{ $chart->cdn() }}"></script>

{{ $chart->script() }}


