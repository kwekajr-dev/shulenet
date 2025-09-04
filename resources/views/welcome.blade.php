<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShuleNet - Complete School Management System</title>
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-light: #a78bfa;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark-bg: #0a0a0f;
            --dark-surface: #161620;
            --dark-surface-light: #1f1f2e;
            --dark-border: #2a2a3a;
            --dark-hover: #252536;
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --text-muted: #71717a;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--dark-bg);
            overflow-x: hidden;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-surface);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(20px);
            background: rgba(10, 10, 15, 0.9);
            border-bottom: 1px solid var(--dark-border);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--dark-border);
        }

        .btn-outline:hover {
            background: var(--dark-surface);
            color: var(--text-primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
        }

        /* Hero Section with Full-Screen Video Background */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.7;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 15, 0.6);
            z-index: -1;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease forwards;
        }

        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease forwards;
        }

        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 1.3rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            color: var(--text-secondary);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease forwards;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-bottom: 3rem;
            animation: fadeInUp 1.2s ease forwards;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 700;
            padding: 1rem 2rem;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(139, 92, 246, 0.4);
        }

        .btn-hero-outline {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 2px solid var(--glass-border);
            color: var(--text-primary);
            padding: 1rem 2rem;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            justify-content: center;
            animation: fadeInUp 1.4s ease forwards;
        }

        .stat-item {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            padding: 1rem 2rem;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Features Section - REDESIGNED */
        .features {
            padding: 6rem 0;
            background: #ffffff;
            position: relative;
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: #0a0a0f;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #71717a;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
        }

        .feature-card:hover {
            transform: perspective(1000px) rotateX(2deg) rotateY(2deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            transition: height 0.3s ease;
        }

        .feature-card:hover::before {
            height: 8px;
        }

        .feature-image-container {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            position: relative;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
        }

        .feature-card:hover .feature-image-container {
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(139, 92, 246, 0.2);
        }

        .feature-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .feature-card:hover .feature-image {
            transform: scale(1.1);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #0a0a0f;
        }

        .feature-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .feature-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .feature-link:hover {
            gap: 1rem;
            color: var(--primary-light);
        }

        /* Feature Hover Animation */
        .feature-hover-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(139, 92, 246, 0.8), rgba(6, 182, 212, 0.8));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
            border-radius: 12px;
            color: white;
            text-align: center;
        }

        .feature-image-container:hover .feature-hover-content {
            opacity: 1;
            transform: translateY(0);
        }

        .hover-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hover-description {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 6rem 0;
            background: var(--dark-bg);
            position: relative;
        }

        .testimonials-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .testimonial-card {
            background: var(--dark-surface);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--dark-border);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border-color: var(--primary);
        }

        .testimonial-content {
            color: var(--text-secondary);
            font-style: italic;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .author-info h4 {
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .author-info p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Pricing Section */
        .pricing {
            padding: 6rem 0;
            background: var(--dark-surface);
            position: relative;
        }

        .pricing-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .pricing-card {
            background: var(--dark-surface-light);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--dark-border);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            text-align: center;
        }

        .pricing-card.featured {
            border-color: var(--primary);
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);
        }

        .pricing-card.featured::before {
            content: 'Most Popular';
            position: absolute;
            top: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-bottom-left-radius: 10px;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-5px);
        }

        .pricing-header {
            margin-bottom: 2rem;
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .pricing-period {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 2rem;
        }

        .pricing-features li {
            padding: 0.5rem 0;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--dark-border);
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        /* Contact Section */
        .contact {
            padding: 6rem 0;
            background: var(--dark-bg);
            position: relative;
        }

        .contact-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .contact-card {
            background: var(--dark-surface);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid var(--dark-border);
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .contact-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .contact-details h3 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .contact-details p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .contact-form {
            background: var(--dark-surface);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--dark-border);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            background: var(--dark-surface-light);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .form-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
        }

        /* Phone Card Styles */
        .phone-card-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        /* Footer */
        .footer {
            background: var(--dark-surface);
            padding: 4rem 0 2rem;
            border-top: 1px solid var(--dark-border);
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-col h3 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.75rem;
        }

        .footer-col ul li a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: var(--text-primary);
            padding-left: 0.5rem;
        }

        .footer-bottom {
            margin-top: 4rem;
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--dark-border);
            color: var(--text-muted);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .phone-card-container {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-container {
                text-align: center;
                padding-top: 4rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .features-grid,
            .testimonials-grid,
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
            .pricing-card.featured {
                transform: scale(1);
            }
            
            .pricing-card.featured:hover {
                transform: translateY(-5px);
            }
        }

        @media (max-width: 480px) {
            .nav-container,
            .hero-container,
            .features-container,
            .testimonials-container,
            .pricing-container,
            .contact-container,
            .footer-container {
                padding: 0 1rem;
            }

            .hero-title {
                font-size: clamp(2rem, 5vw, 3rem);
            }

            .btn {
                padding: 0.75rem 1.25rem;
            }
            
            .contact-form {
                padding: 1.5rem;
            }
            
            .feature-image-container {
                height: 160px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">Shule<span>Net</span></a>
            <nav class="nav-links">
                <a href="#features">Features</a>
                <a href="#testimonials">Testimonials</a>
                <a href="#pricing">Pricing</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="auth-buttons">
                <a href="{{ route('loginForm') }}" class="btn btn-outline">Log In</a>
                <a href="#" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <video class="hero-video-bg" autoplay muted loop>
            <source src="{{ asset('images/back.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    🚀 Trusted by 500+ Schools Across Tanzania
                </div>
                <h1 class="hero-title">
                    Simplify School Management with <span class="gradient-text">ShuleNet</span>
                </h1>
                <p class="hero-description">
                    Mfumo kamili wa usimamizi wa shule ambao unarahisisha utawala, kuboresha mawasiliano, na kuinua uzoefu wa elimu kwa wasimamizi, walimu, wanafunzi, na wazazi.
                </p>
                <div class="hero-buttons">
                    <a href="#" class="btn btn-hero-primary">🚀 Jaribu Free Trial</a>
                    <a href="#" class="btn btn-hero-outline">▶ Watch Demo</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-value">500+</span>
                        <span class="stat-label">Active Schools</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">98%</span>
                        <span class="stat-label">Satisfaction Rate</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <div class="section-badge">COMPREHENSIVE FEATURES</div>
                <h2 class="section-title">Everything You Need to Manage Your School</h2>
                <p class="section-subtitle">
                    ShuleNet inatoa vipengele mbalimbali vilivyoundwa kufanya usimamizi wa shule kuwa rahisi, wazi na wenye ufanisi.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-image-container">
                        <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Staff Management" class="feature-image">
                        <div class="feature-hover-content">
                            <h3 class="hover-title">Staff Management</h3>
                            <p class="hover-description">Easily manage teacher schedules, performance, and professional development.</p>
                        </div>
                    </div>
                    <h3 class="feature-title">Staff Management</h3>
                    <p class="feature-description">
                        Fuatilia taarifa za walimu, ratiba, utendaji, na maendeleo ya kitaaluma.
                    </p>
                    <a href="#" class="feature-link">
                        Learn more
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
               
                
                <div class="feature-card">
                    <div class="feature-image-container">
                        <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Timetable Management" class="feature-image">
                        <div class="feature-hover-content">
                            <h3 class="hover-title">Timetable Management</h3>
                            <p class="hover-description">Create and manage class schedules with drag-and-drop simplicity.</p>
                        </div>
                    </div>
                    <h3 class="feature-title">Timetable Management</h3>
                    <p class="feature-description">
                        Unda na usimamizi ratiba za masomo bila matatizo yoyote.
                    </p>
                    <a href="#" class="feature-link">
                        Learn more
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
               
                
                <div class="feature-card">
                    <div class="feature-image-container">
                        <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Fee Management" class="feature-image">
                        <div class="feature-hover-content">
                            <h3 class="hover-title">Fee Management</h3>
                            <p class="hover-description">Track payments, generate invoices, and manage school finances.</p>
                        </div>
                    </div>
                    <h3 class="feature-title">Fee Management</h3>
                    <p class="feature-description">
                        Fuatilia malipo ya shule na usimamizi wa deni kwa ufanisi.
                    </p>
                    <a href="#" class="feature-link">
                        Learn more
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image-container">
                        <img src="https://images.unsplash.com/photo-1576086213369-97a306d36557?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Parent Portal" class="feature-image">
                        <div class="feature-hover-content">
                            <h3 class="hover-title">Parent Portal</h3>
                            <p class="hover-description">Keep parents informed with real-time updates on student progress.</p>
                        </div>
                    </div>
                    <h3 class="feature-title">Parent Portal</h3>
                    <p class="feature-description">
                        Wazazi waweze kufuatilia maendeleo ya watoto wao kwa nyakati zote.
                    </p>
                    <a href="#" class="feature-link">
                        Learn more
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials">
        <div class="testimonials-container">
            <div class="section-header">
                <div class="section-badge">WHAT OUR CLIENTS SAY</div>
                <h2 class="section-title">Trusted by Schools Across Tanzania</h2>
                <p class="section-subtitle">
                    Maoni kutoka kwa shule mbalimbali zinazotumia mfumo wetu wa usimamizi.
                </p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-content">
                    "ShuleNet imebadilisha kabisa jinsi tunavyofanya kazi shuleni. Sasa tunaweza kufanya kazi kwa ufanisi zaidi na kuokoa muda mwingi."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">JM</div>
                        <div class="author-info">
                            <h4>John Mwambusi</h4>
                            <p>Headmaster, Mlimani Primary</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <p class="testimonial-content">
                    "Mfumo huu umeweza kutatua changamoto nyingi tulizonazo katika usimamizi wa wanafunzi na walimu. Tunashukuru kwa huduma bora."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SA</div>
                        <div class="author-info">
                            <h4>Sarah Ahmed</h4>
                            <p>Academic Director, St. Mary's Secondary</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <p class="testimonial-content">
                    "Kama mzazi, ninaweza kufuatilia maendeleo ya mwanangu shuleni kwa urahisi. Mfumo huu umenipa uhakika na utulivu."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">RK</div>
                        <div class="author-info">
                            <h4>Robert Kato</h4>
                            <p>Parent, Sunshine Academy</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <div class="pricing-container">
            <div class="section-header">
                <div class="section-badge">AFFORDABLE PLANS</div>
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">
                    Chagua mpango unaokufaa zaidi kwa shule yako. Hakikishiwa bei nafuu na ubora wa huduma.
                </p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-name">Basic</h3>
                        <div class="pricing-price">TZS 200,000</div>
                        <div class="pricing-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 200 students</li>
                        <li>Basic student management</li>
                        <li>Exam & grading system</li>
                        <li>Email support</li>
                        <li>Basic reports</li>
                    </ul>
                    <a href="#" class="btn btn-primary" style="width: 100%;">Get Started</a>
                </div>
                
                <div class="pricing-card featured">
                    <div class="pricing-header">
                        <h3 class="pricing-name">Standard</h3>
                        <div class="pricing-price">TZS 350,000</div>
                        <div class="pricing-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 500 students</li>
                        <li>Advanced student management</li>
                        <li>Fee management system</li>
                        <li>Parent portal</li>
                        <li>Priority support</li>
                        <li>Advanced reporting</li>
                    </ul>
                    <a href="#" class="btn btn-primary" style="width: 100%;">Get Started</a>
                </div>
                
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-name">Premium</h3>
                        <div class="pricing-price">TZS 500,000</div>
                        <div class="pricing-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Unlimited students</li>
                        <li>All features included</li>
                        <li>Custom modules</li>
                        <li>Dedicated account manager</li>
                        <li>24/7 priority support</li>
                        <li>Custom reporting</li>
                        <li>API access</li>
                    </ul>
                    <a href="#" class="btn btn-primary" style="width: 100%;">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="contact-container">
            <div class="contact-info">
                <div class="section-header" style="text-align: left;">
                    <div class="section-badge">GET IN TOUCH</div>
                    <h2 class="section-title">Contact Us</h2>
                    <p class="section-subtitle" style="margin: 0;">
                        Tupigie simu, tutumie barua pepe au tembelea ofisi zetu kwa maswali yoyote.
                    </p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p>info@shulenet.co.tz</p>
                        <p>support@shulenet.co.tz</p>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h3>Phone</h3>
                        <p>+255 754 123 456</p>
                        <p>+255 784 987 654</p>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h3>Address</h3>
                        <p>ShuleNet Plaza, Ali Hassan Mwinyi Road</p>
                        <p>Dar es Salaam, Tanzania</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">Send us a Message</h3>
                <form>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" placeholder="Your email">
                    </div>
                    <div class="form-group">
                        <label for="school">School Name</label>
                        <input type="text" id="school" class="form-control" placeholder="Your school name">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" class="form-control" placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="form-submit">Send Message</button>
                </form>
            </div>
        </div>
        
       
        
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <h3>ShuleNet</h3>
                <p style="color: var(--text-secondary); line-height: 1.6;">
                    Mfumo wa kisasa wa usimamizi wa shule unaowezesha shule kuboresha utendaji kazi na ufanisi.
                </p>
            </div>
            
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Webinars</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3>Connect With Us</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2023 ShuleNet. All rights reserved. | Designed with ❤️ for Tanzanian Schools</p>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(10, 10, 15, 0.95)';
                header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.3)';
            } else {
                header.style.background = 'rgba(10, 10, 15, 0.9)';
                header.style.boxShadow = 'none';
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Feature cards animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .testimonial-card, .pricing-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.animationDelay = `${index * 0.1}s`;
            observer.observe(card);
        });
    </script>
</body>
</html>