<style>
  .email{
    margin-left: .5rem;
  padding: 1rem;
  border: 1px solid #eee;
  border-radius: 4px;
  background-color: lightgreen;
  }
  .email:invalid{
    
    background-color: pink;
  }
</style>
<h1 style="text-align:center;" >Subscibe For News Letter</h1>
<center>
<hr style="width:19%;justify-self:center;background-color:#02A237;margin-top:5px;border:4px dashed white;align:center;">
</center>


<div class="emailbox1">  
<form  action="#"method="post" id="form1" class="form">
    <div class="emailbox2">

        <div class="emailbox3" id="email-label">
          
          

           <input style="postion:relative;" class="email" type="text" required  name="email"  pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required="" placeholder="enter you email" title="That aint no email!" value="example765@gmail.com"   id="email-field" >
           
           <input type="submit" value="submit" class="submit" name="submit"  onclick="return checkconfirm()" ><br/><br/>
         
          <center><p style=" text-align:center; width:auto; postion:relative;border-radius:100px 0px 100px 0px;color:purple; background-color:pink; box-shadow:0px 0px 10px  yellow; border: padding 0;width:32pc; justify-content:center;"><i>Register your  email-address for more notification about new item for later </i></p>
</center<br/>

        </div>
        <script>
     
        
        function checkconfirm(){

         if(window.confirm('are you sure? you want to register your email address ?')){
           var a=document.get.ElimentByID("email-field").value;
           if(a>0){
            
            alert('email alredy exit');
               return false;
                 }
           else{
           alert('you email has been  successfully added ');
             return true;
               }
        }
      else{

        return false;
      }
      
    }
    </script>
    </div>
  </form>
</div> 
<footer  >

            
            <div class="foot-panel-2">
                <br>
                <ul>
                    <p style="cursor:pointer"><b>Get to Know Us</b></p>

                    <a style="cursor:pointer">Careers</a>
                    <a style="cursor:pointer">Blog</a>
                    <a style="cursor:pointer">About us</a>
                    <a style="cursor:pointer">Invester Relation</a>
                 
                </ul>

                <ul>
                    <p style="cursor:pointer"><b>Connect with us</b></p>
                    <a style="cursor:pointer">Facebook</a>
                    <a style="cursor:pointer">Twitter</a>
                    <a style="cursor:pointer">Instagram</a>
                </ul>

                <ul>
                    <p style="cursor:pointer"><b>Make Money with Us</b></p>
                    <a style="cursor:pointer">Sell on Swiigy</a>

                    <a style="cursor:pointer">Sell under Swiigy Accelerator</a>
                    <a style="cursor:pointer">Protect and Build Your Brand</a>
                    <a style="cursor:pointer">Swiigy Global Selling</a>
                    <a style="cursor:pointer">Become an Affiliate</a>
                    <a style="cursor:pointer">Fulfilment by Swiigy </a>
                    <a style="cursor:pointer">Advertise Your Products </a>
                    <a style="cursor:pointer">Swiigy Pay on Merchants </a>
                </ul>







                <ul>
                    <p style="cursor:pointer"> <b>Let Us Help You</b>
                    </p>
                    <a style="cursor:pointer">COVID-19 and Swiigy</a>
                    <a style="cursor:pointer">Your Account</a>
                    <a style="cursor:pointer">Returns Centre</a>
                    <a style="cursor:pointer">I100% Purchase Protection</a>
                    <a style="cursor:pointer">Swiigy App Download</a>
                    <a style="cursor:pointer">Help </a>
                </ul>
            </div>
         
        </footer>

        <?php
// connectiog to the database
$servername= "localhost";
$username="root";
$password="";
$dbname="resturant_project";
// create a connection
$c=mysqli_connect($servername,$username,$password,$dbname);

if(isset($_POST['submit'])){
 
  $email=$_POST['email'];
    $qry = "SELECT * FROM news_letter where email ='$email' ";
   $result=mysqli_query($c,$qry);
   $count=mysqli_num_rows($result);
   if($count>0){
    echo"<script>alert('email already exits');</script>";
  
   }
   else{
    
   $insert="INSERT INTO `news_letter` ( `email`) VALUES ( '$email') ";
 
 
     $run=mysqli_query($c,$insert);
      $ans = "";
        if($run) {
                     $ans = "Saved";
                   
                    echo"<script>alert('your email has been added successfully');</script>";
                 }
          else   {
                      $ans = "Not Saved";
                 }
           
}}


?>