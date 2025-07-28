from flask import Flask, render_template, request, redirect, url_for, flash, session
import mysql.connector

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Required for flashing messages

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="your_username",
    password="your_password",
    database="freelance"
)
cursor = db.cursor()

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/register', methods=['POST'])
def register():
    role = request.form['role']
    name = request.form['name']
    email = request.form['email']
    password = request.form['password']  # Hash passwords in real applications!

    # Insert user into the database
    query = "INSERT INTO login (role, name, email, password) VALUES (%s, %s, %s, %s)"
    values = (role, name, email, password)
    cursor.execute(query, values)
    db.commit()

    flash("Registration successful! Please login.", "success")
    return redirect(url_for('login'))

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']

        query = "SELECT * FROM login WHERE email = %s AND password = %s"
        cursor.execute(query, (email, password))
        user = cursor.fetchone()

        if user:
            session['user'] = user[1]  # Storing user name in session
            flash("Login successful!", "success")
            return redirect(url_for('dashboard'))
        else:
            flash("Invalid credentials. Try again.", "danger")

    return render_template('login.html')

@app.route('/dashboard')
def dashboard():
    if 'user' in session:
        return f"Welcome {session['user']}!"
    return redirect(url_for('login'))

if __name__ == '__main__':
    app.run(debug=True)
