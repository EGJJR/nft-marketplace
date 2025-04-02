Here is the refactored code:
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFT Marketplace</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">NFT Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#home">Home</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#upload_nft">Upload NFT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#manage_nfts">Manage NFTs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#transactions">Transactions</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main id="main">
        <section id="home" class="py-5">
            <!-- Home content here -->
        </section>
        <section id="dashboard" class="py-5">
            <!-- Dashboard content here -->
        </section>
        <section id="upload_nft" class="py-5">
            <!-- Upload NFT content here -->
        </section>
        <section id="manage_nfts" class="py-5">
            <!-- Manage NFTs content here -->
        </section>
        <section id="transactions" class="py-5">
            <!-- Transactions content here -->
        </section>
        <section id="login" class="py-5">
            <!-- Login content here -->
        </section>
        <section id="register" class="py-5">
            <!-- Register content here -->
        </section>
    </main>
</body>
</html>
```