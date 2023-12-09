<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="indexStyles.css">
    <title>Přihlášení a registrace</title>
</head>
<body>

    <header>
        <h1>Přihlášení a registrace</h1>
    </header>

    <section>
        <h2>Přihlášení</h2>
        <form action="Services/Users/process_login.php" method="post">
            <label for="loginUsername">Login:</label>
            <input type="text" id="loginUsername" name="loginUsername" required>

            <label for="loginPassword">Heslo:</label>
            <input type="password" id="loginPassword" name="loginPassword" required>

            <div class="form-buttons">
                <button type="submit">Přihlásit se</button>
            </div>
        </form>
    </section>

    <section class="registration-section">
        <h2>Registrace</h2>
        <form action="Services/Users/process_registration.php" method="post" enctype="multipart/form-data">
            <label for="firstName">Jméno:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Příjmení:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Telefon:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" placeholder="123456789" required>

            <label for="gender">Pohlaví:</label>
            <select id="gender" name="gender" required>
                <option value="male">Muž</option>
                <option value="female">Žena</option>
            </select>

            <label for="profilePic">Profilová fotografie:</label>
            <input type="file" id="profilePic" name="profilePic" accept="image/*" required>

            <label for="username">Login:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Heslo:</label>
            <input type="password" id="password" name="password" required>

            <div class="form-buttons">
                <button type="submit">Registrovat</button>
            </div>
        </form>
    </section>

</body>
</html>
