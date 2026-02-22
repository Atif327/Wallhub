<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Template</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
    <style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        background-color: grey;
    }
    table{
        margin: auto;
        background-color: black;
        padding: 20px;
        border-spacing: 15px;
        width: 500px;
        border-radius: 8px;
    }
    .btn-facebook{
        background-color: darkblue;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        display: inline-block;
        border-radius: 4px;
    }
    .btn-twitter{
        background-color: lightblue;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        display: inline-block;
        border-radius: 4px;
    }
    input[type="text"], input[type="email"], input[type="password"]{
        width: 95%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: white;
    }
    .btn-submit{
        background-color: green;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
}
.btn-submit:hover{
    background-color: #219150;

}
.terms{
    font-size: 13px;
    color:white;
    text-align: center;
}
.terms a{
    color: #1da1f2;
    text-decoration: none;
}
.footer {
    text-align: center;
    font-size: 12px;
    margin-top: 10px;
    color: white;
}
</style>
</head>

<body>
    <table>
        <!-- Social Buttons -->
         <tr> 
            <td align="center">
              <a href="https://www.facebook.com" class="btn-facebook">Connect with Facebook</a>
              <a href="https://www.twitter.com" class="btn-twitter">Connect with Twitter</a>
            </td>
         </tr>
         <!-- Or Section -->
          <tr>
            <td align="center" style="color: white;">Or Sign Up with </td>
          </tr>
          <!-- Form Fields -->
           <tr>
            <td>
               <form>
                <input type="text" placeholder="First Name" required>
                <input type="text" placeholder="Last Name" required>
                <input type="email" placeholder="Enter Your Email" required>
                <input type="password" placeholder="password" required>

                <p class="terms">
                    By creating an account ,your agree to our 
                    <a href="https://www.example.com/terms">Terms & Conditions</a> 
                </p>
                <button type="submit" class="btn-submit"> Create account</button>
               </form> 
            </td>
           </tr>
           <!-- Footer -->
            <tr> 
                <td class="footer">
                template by <a href="https://w3layouts.com" style="color:#1da1f2;" > w3layouts </a>
                </td>
            </tr>
    </table>

</body>
</html>