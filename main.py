from flask import Flask, render_template, request, redirect, session, url_for
import pymysql

app = Flask(__name__)
app.secret_key = 'your_secret_key'

# Database connection function
def get_db_connection():
    return pymysql.connect(host='localhost', user='root', password='', database='freelance', cursorclass=pymysql.cursors.DictCursor)

# Home route (optional)
@app.route('/')
def home():
    return "Welcome to the Freelancer Job Portal"

# Login route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT id, role, profile_created FROM login WHERE email=%s AND password=%s", (email, password))
        user = cursor.fetchone()
        conn.close()

        if user:
            session['user_id'] = user['id']
            session['role'] = user['role']

            if user['role'] == 'Freelancer':
                if user['profile_created']:  # profile_created = TRUE
                    return redirect(url_for('freelancer_dashboard'))
                else:
                    return redirect(url_for('create_profile'))
            else:
                return redirect(url_for('client_dashboard'))
        else:
            return "Invalid credentials", 401

    return render_template('login.html')  # Ensure you have a login form

# Create profile route
@app.route('/create_profile', methods=['GET', 'POST'])
def create_profile():
    if 'user_id' not in session:
        return redirect(url_for('login'))

    if request.method == 'POST':
        user_id = session['user_id']
        skills = request.form['skills']

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE login SET profile_created = TRUE WHERE id = %s", (user_id,))
        conn.commit()
        conn.close()

        return redirect(url_for('freelancer_dashboard'))

    return render_template('create_profile.html')

# Freelancer dashboard route
@app.route('/freelancer_dashboard')
def freelancer_dashboard():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    return render_template('freelancer_dashboard.html')

# Client dashboard route
@app.route('/client_dashboard')
def client_dashboard():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    return render_template('client_dashboard.html')

# Logout route
@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))

if __name__ == '__main__':
    app.run(debug=True)
