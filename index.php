<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení a registrace</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand mx-auto" href="#">
            <h1>Přihlášení a registrace</h1>
        </a>
    </div>
</nav>

<section class="container mt-5 bg-dark text-white p-4 rounded">
    <h2 class="mb-4">Přihlášení</h2>
    <form action="process_login.php" method="post">
        <div class="mb-3">
            <label for="loginUsername" class="form-label">Uživatelské jméno:</label>
            <input type="text" class="form-control" id="loginUsername" name="loginUsername" placeholder="Zadejte uživatelské jméno" required>
        </div>

        <div class="mb-3">
            <label for="loginPassword" class="form-label">Heslo:</label>
            <input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="Zadejte email" required>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Přihlásit se</button>
        </div>
    </form>
</section>

<section class="container registration-section mt-5 bg-dark text-white p-4 rounded">
    <h2 class="mb-4">Registrace</h2>
    <form action="process_registration.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="firstName" class="form-label">Jméno:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Zadejte jméno" required>
        </div>

        <div class="mb-3">
            <label for="lastName" class="form-label">Příjmení:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Zadejte příjmení" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Zadejte email" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefon:</label>
            <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{9}" placeholder="123456789" required>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Pohlaví:</label>
            <select class="form-select" id="gender" name="gender" required>
                <option value="male">Muž</option>
                <option value="female">Žena</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="profilePic" class="form-label">Profilová fotografie:</label>
            <input type="file" class="form-control" id="profilePic" name="profilePic" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Uživatelské jméno:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Zadejte uživatelské jméno" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Heslo:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Zadejte heslo" required>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Registrovat</button>
        </div>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
