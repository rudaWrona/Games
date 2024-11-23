<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inis</title>
  <style>
    html {
      font-size: 10px;
    }
    body {
      background-color: rgb(243, 229, 171);
    }
    h1 {
      font-size: 3rem;
      color: rgba(165, 202, 95, 0.76);
      text-align:center;
    }
    .link {
      text-decoration: none;
      color: rgba(165, 202, 95, 0.76);
      }
    .link:hover, .link:active {
      color: white;
      background-color: rgba(165, 202, 95, 0.76);
    }
    #container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top:100px;
      flex-direction: column;
    }
    table, th, td {
      border: 1px solid white;
      border-collapse: collapse;
    }
    th, td {
      width: 150px;
      height: 50px;
      padding: 10px;
      font-size: 2rem;
    }
    .wybrany {
      background-color: rgb(165, 202, 95);
    }
    .bran {
      background-color: gold;
    }
    #pasek {
      width: 300px;
      height: 30px;
      position: relative;
      background-color: #ddd;
    }
    #progres {
      background-color: #4CAF50;
      width: 0px;
      height: 30px;
      position: absolute;
    }
    #przyciski {
      display: flex;
      flex-direction: row;
    }
    #tabelka {
      display: flex;
      justify-content: center;
      flex-direction: column;
      align-items: center;
    }
    #tabelka:fullscreen {
      background-color: rgb(243, 229, 171);
    }
    #tabelka:fullscreen td {
      font-size: 1rem;
      height: 25px;
      width: 75px;
    }
  </style>
</head>
<body>

  <h1><a class="link" href="../../index.html">VanillaDice.pl</a></h1>
  
  <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $gracze = $_POST['gracz'];
      $czas = $_POST['czas'];

      for ($i = count($gracze) - 1; $i >= 0; $i--) {
        if ($gracze[$i] === "")
        {
          unset($gracze[$i]);
        }
      }
      $gracze = array_values($gracze);

      function rysTabele($gracze) {

        echo "<div id='container'>";
        echo "<div id='tabelka'>";
        echo "<table>";
        echo "<tr>";
        echo "<th>Warunek zwycięstwa</th>";
        
          foreach ($gracze as $gracz) {
            echo "<th class='gracz'>$gracz</th>";
          }

        echo "</tr>";
        echo "<tr><td>Wódz nad 6 klanami</td>";
          for ($i = 0; $i < count($gracze); $i++) {
            echo "<td class='pole'></td>";
          }
        echo "</tr>";
        echo "<tr><td>Obecność na 6 polach</td>";
          for ($i = 0; $i < count($gracze); $i++) {
            echo "<td class='pole'></td>";
          }
        echo "</tr>";
        echo "<tr><td>Obecność na polach z 6 miejscami kultu</td>";
          for ($i = 0; $i < count($gracze); $i++) {
            echo "<td class='pole'></td>";
          }
        echo "</tr>";
        echo "<tr><td>Żetony wyczynu</td>";
        for ($i = 0; $i < count($gracze); $i++) {
          echo "<td><button id='plus" . $i + 1 . "'>+</button>&nbsp;<button id='minus" . $i + 1 . "'>-</button>&nbsp;&nbsp;&nbsp;<span id='zeton" . $i + 1 . "'>0</span></td>";
        }
        echo "<tr>";
        echo "</table>";
        echo "<br>";
        echo "<div id='pasek'><div id='progres'></div></div>";
        echo "<br>";
        echo "<div id='przyciski'>";
        echo "<button class='czasMenu' onclick='start()'>Start rundy</button>&nbsp;";
        echo "<button class='czasMenu' onclick='stop()'>Koniec rundy</button>&nbsp;";
        echo "<button class='czasMenu' onclick='pauza()'>Pauza</button>&nbsp;";
        echo "<button class='czasMenu' onclick='wznow()'>Wznów</button>";
        echo "</div>";
        echo "</div>";
        echo "<br>";
        echo "<button onclick='fullscreen()'>Fullscreen</button>";
        echo "</div>";
        
      }
    }
    function liczniki($gracze) {
      echo "<script>";
      for ($i = 0; $i < count($gracze); $i++) {
        echo "document.getElementById('plus" . $i + 1 . "').addEventListener('click', function(){
          let x = Number(document.getElementById('zeton" . $i + 1 . "').innerHTML);
          document.getElementById('zeton" . $i + 1 . "').innerHTML = x + 1;
        });";

        echo "document.getElementById('minus" . $i + 1 . "').addEventListener('click', function(){
          let x = Number(document.getElementById('zeton" . $i + 1 . "').innerHTML);
          if (Number(document.getElementById('zeton" . $i + 1 . "').innerHTML) != 0)
          {
          document.getElementById('zeton" . $i + 1 . "').innerHTML = x - 1;
          }
        });";

      }
      echo "</script>";
    }
    rysTabele($gracze);
    liczniki($gracze);

  ?>
  
  <script>
    
    let warunkiOn = document.querySelectorAll(".pole");
    warunkiOn.forEach(function (warunekOn) {
      warunekOn.addEventListener("click", function() {
        if (this.classList.contains("wybrany")) {
          this.classList.remove("wybrany");
        }
        else {
          this.classList.add("wybrany");
      }
      });
    });

    let wyBrani = document.querySelectorAll(".gracz");
    wyBrani.forEach(function (wyBran){
      wyBran.addEventListener("click", function () {
        wyBrani.forEach(function (b) {
          b.classList.remove("bran");
        });
        this.classList.add("bran");
      });
    });

    var id;
    var isZapauzowany = false;
    var width = 0;
    var czas = document.getElementById("progres");
    var limitCzasu = <?php
    echo "$czas";
    ?>;

    function start() {
      clearInterval(id);
      let width = 0;
      czas.style.backgroundColor = "#4CAF50";
      isZapauzowany = false;
      id = setInterval(frame, 1000);

      function frame() {
        if (!isZapauzowany) {
          if (width >= 100) {
            clearInterval(id);
          } else {
            width += (100 / (limitCzasu * 60));
            czas.style.width = width + '%';
            if (width > 50 && width < 90) {
              czas.style.backgroundColor = "#ff8000";
            } else if (width > 90) {
              czas.style.backgroundColor = "#ff0000";
            }
          }
        }
      }
    }

    function stop() {
      clearInterval(id);
      czas.style.width = 0 + '%';
    }

    function pauza() {
      isZapauzowany = true;
    }

    function wznow() {
      isZapauzowany = false;
    }

    function fullscreen() {
      var elem = document.getElementById("tabelka");

      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
      } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
      }
    }

  </script>
  
</body>
</html>