# Gharbouch ✿
### Boutique Crochet Artisanale en Ligne

> A handmade e-commerce platform for crochet creations — flowers, bags, keychains & accessories — with a custom bouquet builder.

---

## ✨ Features

- 🛍️ **Catalogue** — Dynamic product listing with AJAX filters by category & search by name
- 💐 **Bouquet Builder** — Pick flowers, set quantities (1–8 stems), customize ribbon color, recipient & card message with live price calculation
- 🛒 **Cart** — localStorage-based persistent cart with individual item removal & auto-calculated totals (+7 DT delivery)
- 👤 **Auth** — Registration & login with real-time email validation, PHP sessions, and `password_hash()` security
- 🔑 **Account Page** — Change password & delete account with confirmation modal
- 📦 **Orders** — AJAX order submission with delivery form validation and order confirmation

---

## 📁 Project Structure

```
gharbouch/
├── index.php                  # Home page
├── css/
│   └── stylee.css             # Global stylesheet
├── js/
│   ├── catalogue.js           # AJAX catalogue (filters, search, cart)
│   ├── bouquet.js             # Bouquet builder (flower selection, preview, cart)
│   ├── connexion.js           # Auth (login, register, email check)
│   ├── compte.js              # Account (change password, delete account)
│   └── panier.js              # Cart (display, remove, order)
├── html/
│   ├── catalogue.php          # Catalogue page
│   ├── bouquet.php            # Bouquet builder page
│   ├── panier.php             # Cart & order page
│   ├── connexion.php          # Login / register page
│   └── compte.php             # My account page
├── back/
│   ├── header.php             # Global header (nav, session)
│   ├── footer.php             # Global footer
│   ├── db.php                 # PDO MySQL connection
│   ├── auth.php               # Register, login, email check
│   ├── logout.php             # Logout (session + localStorage)
│   ├── get_produits.php       # Returns filtered products (HTML)
│   ├── get_fleurs.php         # Returns bouquet flowers (HTML)
│   ├── passer_commande.php    # Order processing
│   ├── change_password.php    # Password update
│   └── delete_account.php     # Account deletion
├── database/
│   └── gharbouch.sql          # SQL schema + seed data
└── images/                    # Product images
```

---

## 🛠️ Tech Stack

| Layer | Technologies |
|-------|-------------|
| **Frontend** | HTML5, CSS3, JavaScript ES6, AJAX (Fetch API) |
| **Backend** | PHP 8+, MySQLi |
| **Database** | MySQL |
| **Server** | XAMPP (Apache + MySQL) |

---

## ⚙️ Installation

### Prerequisites
- [XAMPP] (Apache + MySQL + PHP 8+)
- A modern browser

### Steps

**1. Clone the repository**
```bash
cd C:/xampp/htdocs
git clone https://github.com/YOUR_USERNAME/gharbouch.git
```

**2. Set up the database**
1. Start XAMPP → launch Apache & MySQL
2. Open [phpMyAdmin](http://localhost/phpmyadmin)
3. Create a new database named `gharbouch`
4. Import `database/gharbouch.sql`

**3. Configure the connection**

Check `back/db.php` and update if needed:
```php
$host   = 'localhost';
$dbname = 'gharbouch';
$user   = 'root';
$pass   = '';   // empty by default on XAMPP
```

**4. Run**
```
http://localhost/gharbouch/
```

---

## 🗄️ Database Schema

<details>
<summary>Click to expand</summary>

```sql
CREATE TABLE utilisateurs (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  prenom          VARCHAR(100) NOT NULL,
  email           VARCHAR(150) UNIQUE NOT NULL,
  mot_de_passe    VARCHAR(255) NOT NULL,
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  nom   VARCHAR(100) NOT NULL,
  slug  VARCHAR(100) UNIQUE NOT NULL,
  icone VARCHAR(10)
);

CREATE TABLE produits (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nom          VARCHAR(150) NOT NULL,
  description  TEXT,
  prix         DECIMAL(10,2) NOT NULL,
  image        VARCHAR(255),
  badge        VARCHAR(50),
  categorie_id INT,
  date_ajout   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

CREATE TABLE fleurs_bouquet (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  nom   VARCHAR(100) NOT NULL,
  slug  VARCHAR(100) UNIQUE NOT NULL,
  prix  DECIMAL(10,2) NOT NULL,
  emoji VARCHAR(10)
);

CREATE TABLE commandes (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  nom_client     VARCHAR(150) NOT NULL,
  email_client   VARCHAR(150) NOT NULL,
  utilisateur_id INT NULL,
  total          DECIMAL(10,2),
  date_commande  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

</details>

---

## 👩‍💻 Author

**Emna Ghourabi** — Computer Science & Multimedia student at Institut Supérieur d'Informatique et Multimédia de Gabès

---

*Gharbouch ✿ — Made with love ⋆｡‧˚ʚ🍓ɞ˚‧｡⋆*
