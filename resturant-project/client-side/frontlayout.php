<html>
    <head>
        <style>

.container{
  
    height: 110vh;
    display:flex;
   background-color: whitesmoke;
}

.container .container-left{
  
    width:60%;
    margin-top: 7%;
    height:93.5%;
    text-align:center;
  
  }
  .container .container-right{
      width: 40%;
      margin-top:8%;
     
      height:95%;
     
      justify-content:center;
      align-items:center;
  
  }  
  .container .container-right  .img{
      width:68%;
      animation: round 25s infinite linear;
      position: relative;
      margin-top:18%;
      margin-right:18%;
     
  
    
      
  
  }
  
  @keyframes round {
      0%{
          transform: translate(0deg);
      }
      100%{
          transform: translate(-0%,-0%) rotate(360deg);
      }
  }
  
.btn1{
    width:200px;
    height:60px;
    font-size:30px;
    border-radius:50px;
    color:white;
    outline:none;
    border:none;
    cursor: pointer;
    box-shadow:5px 5px 10px  grey;
}
            </style>
</head>
<body>
<div class="container">
        <div class="container-left">
           <h1><span style="font-family:Curlz MT; font-size:80px;color:green;">Let's Eat </span> <span style="font-family:Curlz MT; font-size:70px;color:orange;">Together</span></h1>
           <p style="font-family:Curlz MT; font-size:40px;color:red;">The Best Resturant</p>
           <br>
           <br>
           <a href="foods.php"><button  class="btn1" style="background-color:orange;">View More</button></a>
           <a href="foods.php"><button class="btn1" style="background-color:green;">Order Now</button></a>
           <br/>
           <img src="photo/eating.png" width="35%" style="margin-top:3%">
        </div>
        <div class="container-right">
       
          <img class="img" src="photo/hero.png" >

</div>
 </div>
</body>
 </html>