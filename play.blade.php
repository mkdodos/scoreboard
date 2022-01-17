<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
  <link href="{{ asset('/css/nav.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/css/score/board.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/table.css') }}">

  <title>計分版</title>
  <style>
    .game {
      display: flex;
      align-items: center;
      /* border: 1px solid; */
      justify-content: space-between;


    }

    .game .num {
      /* flex-grow: 1; */
      color: #fff;
      background-color: #009578;
      font-size: 50px;
      margin: 0 30px 0px 30px;
    }
  </style>

</head>

<body>

  <!-- 計分版 -->
  <div id="app">

    <div class="list" v-show="!is_show">


      <div class="col-md-4 offset-md-4">

        <div class="input-group">
          <!-- <span class="input-group-text">First and last name</span> -->
          <select class="form-select" id="playerA" aria-label="Default select example">
            <option selected>選手A</option>
            <option value="馬克">馬克</option>
            <option value="宜君">宜君</option>
            <option value="愷軒">愷軒</option>
          </select>

          <span class="input-group-text" @click="openGame">VS</span>

          <select class="form-select" id="playerB" aria-label="Default select example">
            <option selected>選手B</option>
            <option value="馬克">馬克</option>
            <option value="宜君">宜君</option>
            <option value="愷軒">愷軒</option>
          </select>
          <!-- <input type="text" aria-label="First name" class="form-control">
  <input type="text" aria-label="Last name" class="form-control"> -->
        </div>

      </div>

      ${ board_id }
     
      <!-- <details></details> -->
      <table class="content-table">
        <thead>
          <!-- <th>Time</th>
          <th>PointA</th>
          <th>PointB</th> -->
          <tr>
            <th>時間</th>
            <th>${ gameA }</th>
            <th></th>
            <th></th>
            <th>${ gameB }</th>
          
          </tr>




        </thead>
        <tbody>
          <tr v-for="(point,idx) in details">
            <td> ${ point.play_time | toTime }</td>
            <td><i v-show="point.pointA > point.pointB" class="material-icons">grade</i></td>
            <td> ${ point.pointA }</td>
            <td> ${ point.pointB }</td>
            <td><i v-show="point.pointA < point.pointB" class="material-icons">grade</i></td>

          </tr>
        </tbody>
      </table>
    </div>

    <!-- 分數顯示 -->

    <div v-show="is_show">
      <div class="game">
        <div id="gameA" class="num" onclick="changeGameA()"></div>
        <i class="material-icons nav-icon" @click="resetGame()">restart_alt</i>
        <div id="gameB" class="num" onclick="changeGameB()"></div>
      </div>
      <div class="score">
        <div id="pointA" class="num" onClick="changeA(1)"></div>
        <div class="num reset" @click="resetPoint()">-</div>
        <div id="pointB" class="num" onClick="changeB(1)"></div>
      </div>
    </div>







    <!-- 分數控制列 -->
    <div class="nav">
      <a class="plus nav-link" href="#" onClick="changeA(1)">
        <i class="material-icons nav-icon">arrow_circle_left</i>
      </a>
      <a class="nav-link" href="#" onClick="changeA(-1)">
        <i class="material-icons nav-icon">remove_circle_outline</i>
      </a>
      <a class="nav-link" href="#" @click="showDetail()">
        <i class="material-icons nav-icon">list_alt</i>
      </a>
      <a class="nav-link" href="#" onClick="changeB(-1)">
        <i class="material-icons nav-icon">remove_circle_outline</i>
      </a>
      <a class="plus nav-link" href="#" onClick="changeB(1)">
        <i class="material-icons nav-icon">arrow_circle_right</i>
      </a>
    </div>

  </div>
</body>

</html>





<!-- <script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script> -->
<script src="https://www.gstatic.com/firebasejs/5.5.6/firebase.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
  const firebaseConfig = {
    apiKey: "AIzaSyBnsxe8kvHrgLNe0UQ1xuUz2oY2ESd5SOE",
    authDomain: "score-96fcd.firebaseapp.com",
    databaseURL: "https://score-96fcd-default-rtdb.firebaseio.com",
    projectId: "score-96fcd",
    storageBucket: "score-96fcd.appspot.com",
    messagingSenderId: "17881397721",
    appId: "1:17881397721:web:2c78f92ccee5fbd798c1c8",
    measurementId: "G-8WYVCD8JNM"
  };


  // Initialize Firebase
  const app = firebase.initializeApp(firebaseConfig);

  let db = app.firestore();

  const settings = {
    timestampsInSnapshots: true
  };

  db.settings(settings);

  var ref = db.collection('scoreboard');

  var ref_details = db.collection('scoreboard_details');


  

  // 用vue呈現資料
  var appVue = new Vue({
    el: '#app',
    data: {
      gameA: '',
      gameB: '',
      details: [],
      is_show: false,
      board_id: ''
    },
    filters: {
      toTime: (value) => {
        var date = new Date(value);
        return date.getHours() + ':' + date.getMinutes();
      }
    },
    methods: {
      showDetail() {
        this.is_show = !this.is_show
      },
      openGame() {
        // console.log(document.getElementById("playerA").value);
        // console.log(document.getElementById("playerB").value);
        ref.add({
          playerDate: new Date().toJSON().slice(0, 10).replace(/-/g, '/'),
          playerA: document.getElementById("playerA").value,
          playerB: document.getElementById("playerB").value
        }).then((doc) => {
          this.board_id = doc.id;
          console.log(doc.id);
          this.is_show = !this.is_show
        })
      },
      resetGame() {
        var gameA = document.getElementById('gameA').textContent;
        var gameB = document.getElementById('gameB').textContent;
        ref.doc(this.board_id).update({
          gameA: gameA,
          gameB: gameB
        })
        //將 game 的值存起來,作為顯示在表格之用
        this.gameA = gameA;
        this.gameB = gameB;
        database.ref('/0').update({
          game: 0
        });
        database.ref('/1').update({
          game: 0
        });
        this.is_show = !this.is_show
      },
      resetPoint() {
        var pointA = document.getElementById('pointA').textContent;
        var pointB = document.getElementById('pointB').textContent;
        var gameA = document.getElementById('gameA').textContent;
        var gameB = document.getElementById('gameB').textContent;
        ref_details.add({
          board_id: this.board_id,
          pointA: pointA,
          pointB: pointB,
          play_time: Date.now()
        });

        //平手時先出現提示,再歸 0
        if(pointA * 1 == pointB * 1){
          if(confirm("平手")){
            database.ref('/0').update({
              point: 0
            });
            database.ref('/1').update({
              point: 0
            });
          }
        }
        
       
        //加 game
        if(pointA * 1 > pointB * 1){
          database.ref('/0').update({
            game: gameA * 1 + 1 
          });
          
        } 
        
        if(pointA * 1 < pointB * 1){
          database.ref('/1').update({
            game: gameA * 1 + 1 
          });          
        } 
      
       
      }
    },
    delimiters: ['${', '}'],
    mounted() {
     
    
      // ref_details.orderBy('play_time', 'desc').onSnapshot(querySnapshot => {
      //   this.details = [];
      //   querySnapshot.forEach(doc => {
      //     this.details.push(doc.data())                             
      //   });
      // });


    }
    ,
     updated() {
     
      ref_details.where('board_id','==',this.board_id).orderBy('play_time', 'desc').get().then(querySnapshot => {
        this.details = [];
        querySnapshot.forEach(doc => {
          this.details.push(doc.data())                             
        });
      });
     
     
     
     
     
        //onSnapshot 資料有變動時,重新取得資料
     
     
     
     
      // ref_details.where('board_id','==',this.board_id).orderBy('play_time', 'desc').onSnapshot(querySnapshot => {
      //   this.details = [];
      //   querySnapshot.forEach(doc => {
      //     this.details.push(doc.data())                             
      //   });
      // });
     } 
  })




  //取得資料庫物件
  // const app2 = firebase.initializeApp({
  //   databaseURL: "https://score-96fcd-default-rtdb.firebaseio.com/"
  // });
  const database = firebase.database();

  const gameA = document.getElementById('gameA');
  const gameB = document.getElementById('gameB');

  const pointA = document.getElementById('pointA');
  const pointB = document.getElementById('pointB');
  


  //監聽資料有變動時要做的事
  database.ref('/').on('value', e => {
    gameA.textContent = e.val()[0].game;
    gameB.textContent = e.val()[1].game;
    pointA.textContent = e.val()[0].point;
    pointB.textContent = e.val()[1].point;
  });


  function changeGameA() {
    var old_point = gameA.textContent * 1;
    var updated_point = old_point + 1;
    database.ref('/0').update({
      game: updated_point
    });
  }


  function changeGameB() {
    var old_point = gameB.textContent * 1;
    var updated_point = old_point + 1;
    database.ref('/1').update({
      game: updated_point
    });
  }


  function changeA(incremen) {
    var old_point = pointA.textContent * 1;
    var updated_point = old_point + incremen;
    //避免出現負數
    if (updated_point < 0) {
      updated_point = 0;
    }
    //更新資料
    database.ref('/0').update({
      point: updated_point
    });
  }


  function changeB(incremen) {
    var old_point = pointB.textContent * 1;
    var updated_point = old_point + incremen;
    if (updated_point < 0) {
      updated_point = 0;
    }
    database.ref('/1').update({
      point: updated_point
    });
  }

  //分數新增後重設
  // function reset() {
  //   ref_details.add({
  //     pointA: pointA.textContent,
  //     pointB: pointB.textContent,
  //     play_time: Date.now()
  //   });
  //   database.ref('/0').update({
  //     point: 0
  //   });
  //   database.ref('/1').update({
  //     point: 0
  //   });
  // }

  // function resetGame() {
  // console.log(gameB.textContent); 
  // ref.add({
  //   playDate: new Date().toJSON().slice(0, 10).replace(/-/g, '/'),
  //   playerA: '大大',
  //   playerB: '馬克',
  //   gameA: gameA.textContent,
  //   gameB: gameB.textContent
  // });


  // }
</script>