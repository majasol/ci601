const express = require("express");
const app = express();
require("dotenv").config();
const path = require("path");
const db = require("./server/db.js");
const { auth } = require("express-openid-connect");
const multer = require("multer");
const upload = multer({ dest: 'uploads/' }); // Directory for storing uploaded files
const port = process.env.PORT;

db.connect((err) => {
    if (err) console.error("Database connection failed:", err);
    else console.log("Connected to MySQL database.");
});

const config = {
    authRequired: false,
    auth0Logout: true,
    secret: process.env.SECRET,
    baseURL: process.env.BASEURL,
    clientID: process.env.CLIENTID,
    issuerBaseURL: process.env.ISSUER,
}; 

app.get("/items", (req, res) => {
    const query = 'SELECT * FROM items'; // Replace with your actual table name and columns
    db.query(query, (err, results) => {
        if (err) {
            console.error("Error fetching items from database:", err);
            res.status(500).send("Error fetching items");
        } else {
            res.json(results); // Return the data as JSON
        }
    });
});

app.post("/add-item", upload.single('image'), (req, res) => {
    const { user_id, name, category, sub_category, color, times_used, cost } = req.body;
    
    // Store the image URL (you might need to adjust the path based on your server setup)
    const image_url = `/uploads/${req.file.filename}`;

    const created_at = new Date().toISOString(); // Current timestamp for created_at

    const query = `
        INSERT INTO clothes (user_id, name, image_url, category, sub_category, color, times_used, cost, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;
    
    db.query(query, [user_id, name, image_url, category, sub_category, color, times_used, cost, created_at], (err, result) => {
        if (err) {
            console.error("Error inserting item into database:", err);
            res.status(500).send("Error adding item");
        } else {
            console.log("Item added successfully");
            res.status(200).send("Item added");
        }
    });
});




app.set("view engine", "ejs");

app.use(express.static(__dirname));
app.use(express.urlencoded({ extended: true }));

// auth router attaches /login, /logout, and /callback routes to the baseURL
app.use(auth(config));

app.get("/", (req, res) => {
    const isAuthenticated = req.oidc.isAuthenticated();
    const user = req.oidc.user; // Get user info if logged in
    res.sendFile(path.join(__dirname, 'views', 'index.html')); // Serve the static HTML file
});

app.get("/auth-status", (req, res) => {
    const isAuthenticated = req.oidc.isAuthenticated();
    res.json({ isAuthenticated, user: req.oidc.user || null });
});



// ** Static Pages **
app.get("/index", (req, res) => res.sendFile(path.join(__dirname, "views", "index.html")));

app.listen(port, () => {
    console.log('Server started on port 3306');
});  