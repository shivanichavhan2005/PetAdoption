# Pet Adoption System (PHP + MySQL)

A simple DBMS mini-project: users can browse pets and request adoption;
admins can add/edit pets and approve/reject requests.

## Setup (XAMPP)

1. Copy this whole `PetAdoption` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Go to `http://localhost/phpmyadmin`, click **Import**, and import `schema.sql`.
   (This creates the `pet_adoption` database and its tables, plus 3 sample pets.)
4. Visit `http://localhost/PetAdoption/create_admin.php` **once** in your browser.
   This creates the admin login:
   - Email: `admin@petadopt.com`
   - Password: `admin123`
   Then delete `create_admin.php` (or just don't run it again).
5. Visit `http://localhost/PetAdoption/home.php` to see the site.

## Structure

- `dbconnect.php` — database connection
- `home.php` — browse available pets
- `pet_details.php` — view one pet + submit adoption request
- `login.php` / `register.php` / `logout.php` — user auth
- `my_requests.php` — a logged-in user's own adoption request history
- `admin/dashboard.php` — admin: list/add/edit/delete pets
- `admin/add_pet.php`, `admin/edit_pet.php` — pet forms
- `admin/manage_requests.php` — approve/reject adoption requests
- `includes/header.php`, `includes/footer.php` — shared layout
- `css/style.css` — styling

## Database Tables

- **users** — id, name, email, password (hashed), phone, address, role (admin/user)
- **pets** — id, name, species, breed, age, gender, description, status (available/pending/adopted)
- **adoption_requests** — links a user to a pet with a message and status (pending/approved/rejected)

## Notes for your report

- Passwords are hashed with `password_hash()` / verified with `password_verify()` — never stored as plain text.
- All SQL queries use **prepared statements** (`mysqli->prepare` + `bind_param`) to prevent SQL injection.
- `adoption_requests` has a many-to-one relationship with both `pets` and `users` — a natural talking point for your ER diagram / normalization section.
