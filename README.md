
---

# 🌐 Globus E-Learning

Globus E-Learning is a complete online learning platform, similar to other modern e-learning systems.
The platform allows users to purchase courses, study lessons, attempt quizzes and mock tests, and interact through real-time communication features.

It supports **video calls, voice calls, and live chat**, offering a classroom-like interactive experience for learners.

## 🧰 Tech Stack

* **Laravel** – Backend development
* **Blade** – Frontend templating
* **Node.js + Socket.IO** – Real-time communication (chat, video call, voice call)
* **Stripe** – Secure online payment integration

Overall, Globus E-Learning is designed to offer a smooth and interactive digital learning environment.

---

## 🛠 Setup Guide

### 1️⃣ Clone the Repository

```bash
git clone <your-repo-url>
cd <your-project-folder>
```

---

### 2️⃣ Install Laravel Dependencies

```bash
composer install
php artisan serve
```

---

### 3️⃣ Install Frontend Dependencies (Room Directory)

Open a new terminal:

```bash
cd room
npm install
npm run dev
```

---

### 4️⃣ Start Node Real-Time Server (node-chat-server)

Open another terminal:

```bash
cd node-chat-server
npm install
npm run dev
```

---

### 5️⃣ Environment Setup

Copy `.env.example` and create your `.env` file:

```bash
cp .env.example .env
```

Fill in your local configuration (DB, Stripe keys, etc.).

---

### 6️⃣ Run Migrations

Finally, migrate your database:

```bash
php artisan migrate
```

---

