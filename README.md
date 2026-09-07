# VIOLA | Find Your Amazing Home

A full-stack real estate web application that allows users to discover, search, and browse luxury residences curated for modern living. This project features a dynamic frontend, an integrated server-side PHP routing engine, and a relational MySQL database to handle properties and listings.

🌐 **[Live Demo](https://viola-realestate.great-site.net/)**

---

## 🚀 Features
- **Dynamic Property Catalog:** Clean, modern user interface showcasing available real estate listings.
- **Advanced Search Filters:** Search capabilities built directly into the home page for quick navigation.
- **Relational Database Management:** Backend storage system keeping secure track of property data, images, and agent profiles.
- **Centralized Layout System:** Uses a shared structural PHP architecture (`header.php` / `footer.php`) to keep page components lightning-fast and uniform across sub-pages.
- **Complete Brand Formatting:** Includes responsive cross-platform configurations alongside custom global assets like high-resolution media and favicon branding.

---

## 🛠️ Tech Stack
- **Frontend:** HTML5, CSS3 (Custom grid & flexbox architecture), JavaScript (ES6+)
- **Backend:** PHP (Strict-type validation structure)
- **Database:** MySQL / SQL (Relational database framework)
- **Fonts:** Playfair Display & Nunito via Google Fonts

---

## 💻 Local Installation & Setup

If you want to run this application on your local machine, follow these steps using a local development server like **XAMPP**:

### 1. Clone the Files
Place the repository folders directly into your local server's public directory:
- **Windows:** `C:\xampp\htdocs\viola\`
- **Mac:** `/Applications/XAMPP/htdocs/viola/`

### 2. Configure the Database
1. Open your XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open your web browser and navigate to `http://localhost/phpmyadmin/`.
3. Create a brand new database named exactly `viola_realestate`.
4. Click on the database, select the **Import** tab, choose the `.sql` schema file included in this project, and click **Go**.

### 3. Launch the Site
Open your browser and navigate to your local root link:
```text
http://localhost/viola/
```

---

## ☁️ Deployment Environment

- **Hosting Provider:** [InfinityFree](https://infinityfree.com) (Free Tier Always-On cloud hosting)
- **File Transfer:** Configured for seamless deployment mapping onto native `/htdocs` web server root.
- **Database Architecture:** Live environment connections utilize external environment string mapping configurations for secure remote database syncs.
