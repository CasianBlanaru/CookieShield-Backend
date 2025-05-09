<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CookieShield - Cookie-Consent-Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff4433;
            --primary-dark: #e63a2a;
            --secondary: #333;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .logo span {
            margin-left: 10px;
        }
        
        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            margin-left: 30px;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .hero {
            padding: 150px 0 100px;
            text-align: center;
            background: linear-gradient(135deg, #fff 0%, #f5f5f5 100%);
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .hero p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 800px;
            margin: 0 auto 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .features {
            padding: 80px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 20px;
        }
        
        .section-title p {
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .feature-card p {
            color: var(--gray);
        }
        
        .cta {
            padding: 80px 0;
            text-align: center;
            background-color: var(--primary);
            color: white;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta p {
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        .btn-light {
            background-color: white;
            color: var(--primary);
        }
        
        .btn-light:hover {
            background-color: #f5f5f5;
        }
        
        footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-column ul li a:hover {
            color: white;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    🛡️<span>CookieShield</span>
                </div>
                <div class="nav-links">
                    <a href="#features">Funktionen</a>
                    <a href="/api">API</a>
                    <a href="https://github.com/yourusername/cookieshield" target="_blank">GitHub</a>
                    <a href="/docs">Dokumentation</a>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Cookie-Consent-Management leicht gemacht</h1>
            <p>CookieShield ist eine leistungsstarke Lösung, die Ihnen hilft, DSGVO-konformes Cookie-Consent-Management einfach zu implementieren. Schützen Sie die Privatsphäre Ihrer Benutzer und halten Sie gesetzliche Vorschriften ein.</p>
            <a href="#features" class="btn">Mehr erfahren</a>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>Warum CookieShield?</h2>
                <p>CookieShield bietet alles, was Sie für ein effektives und gesetzeskonformes Cookie-Management benötigen.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>DSGVO-konform</h3>
                    <p>Erfüllen Sie alle Anforderungen der DSGVO und anderer internationaler Datenschutzgesetze mit unserer vollständig konformen Lösung.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Einfache Integration</h3>
                    <p>Integration in wenigen Minuten dank unserer benutzerfreundlichen API und umfassender Dokumentation.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3>Anpassbar</h3>
                    <p>Passen Sie das Aussehen und Verhalten des Cookie-Consent-Banners an Ihr Markendesign an.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Umfassende Analytik</h3>
                    <p>Verfolgen und analysieren Sie Einwilligungsraten und Benutzerverhalten mit detaillierten Berichten.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>Mehrsprachig</h3>
                    <p>Unterstützung für mehrere Sprachen, um globale Benutzer in ihrer bevorzugten Sprache anzusprechen.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔄</div>
                    <h3>Cookie-Kategorisierung</h3>
                    <p>Organisieren Sie Cookies in Kategorien und ermöglichen Sie Benutzern, granulare Einwilligungen zu erteilen.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Bereit, loszulegen?</h2>
            <p>Schützen Sie die Privatsphäre Ihrer Benutzer und machen Sie Ihre Website gesetzeskonform mit CookieShield.</p>
            <a href="/docs" class="btn btn-light">Zur Dokumentation</a>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>CookieShield</h3>
                    <ul>
                        <li><a href="#features">Funktionen</a></li>
                        <li><a href="/pricing">Preise</a></li>
                        <li><a href="/docs">Dokumentation</a></li>
                        <li><a href="/blog">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Ressourcen</h3>
                    <ul>
                        <li><a href="/guides">Anleitungen</a></li>
                        <li><a href="/api">API-Referenz</a></li>
                        <li><a href="/changelog">Changelog</a></li>
                        <li><a href="/status">Status</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Unternehmen</h3>
                    <ul>
                        <li><a href="/about">Über uns</a></li>
                        <li><a href="/contact">Kontakt</a></li>
                        <li><a href="/careers">Karriere</a></li>
                        <li><a href="/legal">Rechtliches</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Rechtliches</h3>
                    <ul>
                        <li><a href="/privacy">Datenschutz</a></li>
                        <li><a href="/terms">AGB</a></li>
                        <li><a href="/cookie-policy">Cookie-Richtlinie</a></li>
                        <li><a href="/gdpr">DSGVO</a></li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2025 CookieShield. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>
</body>
</html>
