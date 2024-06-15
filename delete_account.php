<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <style>
   
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('https://img.pikbest.com/ai/illus_our/20230422/ca48cc2ea0c23c387add249759ba3a33.jpg!w700wp');
    background-size: cover;
    background-position: center;
    color: #FFF; /* White text color */
}

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        h2 {
            color: #ff9800; /* Orange color */
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #ccc;
            line-height: 1.6;
        }

        form {
            text-align: center;
        }

        input[type="submit"] {
            background-color: red; /* Orange button */
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #f57c00; /* Darker orange on hover */
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50; /* Green link color */
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Your Account</h2>
        <p>Are you sure you want to delete your account?</p>
        <form action="delete_account_process.php" method="post">
            <input type="submit" name="delete_account" value="Delete Account">
        </form>
        <p><a href="profile.php">Back to Profile</a></p>
    </div>
</body>
</html>
