<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BudgetTrack | EVSU–Ormoc</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-fixed bg-cover bg-center" style="background-image: linear-gradient(rgba(255,255,255,.53), rgba(255,255,255,.53)), url('assets/img/bg.png');">
    <header class="header">
        <div class="container">
            <div class="nav">
                <div class="brand">
                    <span>BudgetTrack</span>
                </div>
                <nav class="nav-links">
                    <a href="#policies">Policies</a>
                    <a href="#help">Help</a>
                    <a href="#contact">Contacts</a>
                    <a href="login.php">Login</a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="hero" style="background-image: linear-gradient(rgba(255,255,255,.50), rgba(255,255,255,.50)), url('img/bg.png'); background-repeat:no-repeat; background-position: top center; background-size: cover;">
            <div class="container">
                <div class="hero-content">
                    <h1>Monitor your budget using our system</h1>
                    <p>BudgetTrack provides real-time visibility into budget allocations, expenditures, and remaining balances for EVSU-Ormoc Campus departments. Streamline financial monitoring with automated updates and comprehensive reporting.</p>
                    <a href="#policies" class="cta-btn">Learn More</a>
                </div>
        </section>
        <section id="policies" class="section">
            <div class="container">
                <h2>Policies</h2>
                <p class="lead">Core governance references embedded into BudgetTrack for compliance and transparency.</p>
                <div class="grid">
                    <div class="card">
                        <div class="card-icon">📘</div>
                        <h3>CHED CMO-No.20-S2011</h3>
                        <p>Standard allocation categories (Instruction, Research, Extension, Production, Administrative) used for tagging and reporting.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">📋</div>
                        <h3>CHED–DBM JMC 2017-1A</h3>
                        <p>Reallocation rules with justification and approvals. The system records requests and preserves an audit trail.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">📑</div>
                        <h3>RA 9184 (GPRA)</h3>
                        <p>PPMP → APP → PR workflow with multi-stage reviews to ensure economy, efficiency, and accountability.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🏷️</div>
                        <h3>Funds & Tagging</h3>
                        <p>Fiduciary, Non‑Fiduciary, and TOSI funds supported with usage restrictions reflected across modules.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🔐</div>
                        <h3>Role‑Based Access</h3>
                        <p>Admin, Budget Staff, Department Head, and Department User roles restrict actions to responsibilities.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🧾</div>
                        <h3>Data Privacy</h3>
                        <p>Accounts and financial records are protected; access is logged for audit trail and accountability.</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="help" class="section help-section">
            <div class="container">
                <h2>Help</h2>
                <p class="lead">Quick guidance for getting started with BudgetTrack.</p>
                <div class="grid">
                    <div class="card">
                        <div class="card-icon">🔑</div>
                        <h3>Login & Roles</h3>
                        <p>Admins manage accounts and roles. Department users can view budgets; heads can submit PPMP and track utilization.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">📊</div>
                        <h3>Dashboard</h3>
                        <p>Real-time balances, allocations, and spending progress by department and category.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🧮</div>
                        <h3>Reports</h3>
                        <p>Generate allocation/utilization summaries with filters by fiscal year, department, and date range.</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="contact" class="section">
            <div class="container">
                <h2>Contact</h2>
                <p class="lead">Get in touch with the Budget Office for support and coordination.</p>
                <div class="grid">
                    <div class="card">
                        <div class="card-icon">🏛️</div>
                        <h3>Office</h3>
                        <p>Budget Office, EVSU–Ormoc Campus<br/>Mon–Fri, 8:00 AM – 5:00 PM</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">✉️</div>
                        <h3>Email</h3>
                        <p>budgetoffice@evsu-oc.edu.ph</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🌐</div>
                        <h3>Portal</h3>
                        <p>Access the system on campus network using your assigned credentials.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <h2>Key Features</h2>
                <div class="grid">
                    <div class="card">
                        <div class="card-icon">💰</div>
                        <h3>Budget Allocation</h3>
                        <p>Easily manage and track budget allocations across departments with real-time updates and automated calculations.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">📈</div>
                        <h3>Financial Reports</h3>
                        <p>Generate comprehensive reports for compliance, transparency, and informed decision-making processes.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">🔒</div>
                        <h3>Secure Access</h3>
                        <p>Role-based access control ensures data security while maintaining transparency for authorized users.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <div>© <span id="y"></span> EVSU–Ormoc Campus • BudgetTrack</div>
            <div>Contact us: <span style="color:#800000; font-weight:700;">skypian@gmail.com</span> / <span style="font-weight:700;">EVSU OCC</span></div>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>


