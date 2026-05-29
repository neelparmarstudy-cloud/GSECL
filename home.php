<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSECL Daily Work Logbook System</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="foot.css">
    <link rel="stylesheet" href="main1.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                </svg>
                <span class="logo-text">Daily Work Logbook Record System (GSECL)</span>
            </div>

            <div class="header-buttons">
              <a href="login.php"><button class="btn btn-outline">Log In</button></a>  
              <a href="signup.php"><button class="btn btn-outline">Request for Sign up</button></a>
            
            </div>
        </div>
    </header>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="container hero-container">
                <div class="hero-content">
                    <h1 class="hero-title">Streamline Power Plant Operations with Digital Logging</h1>
                    <p class="hero-description">
                        The Daily Work Logbook System for Gujarat State Electricity Corporation Limited enhances operational efficiency, accuracy, and compliance.
                    </p>
                </div>
                <div class="hero-image-container">
                    <img src="https://th.bing.com/th/id/OIP.73KKjs_8-RpRbWKCp4VacQHaEx?rs=1&pid=ImgDetMain"
                     alt="GSECL Logbook Dashboard Preview" class="hero-image">
                    
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Powerful Features for Power Plant Operations</h2>
                    <p class="section-description">
                        Our comprehensive logbook system is designed specifically for the needs of power plant operations.
                    </p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                         <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <h3 class="feature-title">Real-Time Data Entry</h3>
                        <p class="feature-description">Log operational conditions, equipment status, and incidents with automatic timestamping for each entry.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h3 class="feature-title">Standardized Logging</h3>
                        <p class="feature-description">Predefined input fields maintain uniformity and accuracy with validation checks to prevent incorrect entries.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Role-Based Access</h3>
                        <p class="feature-description">Secure login system for Shift Engineers, Plant Supervisors, and Compliance Teams with role-based permissions.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <h3 class="feature-title">Shift Handover</h3>
                        <p class="feature-description">Seamless shift transitions with real-time collaboration and notifications for critical updates.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="3" y1="15" x2="21" y2="15"></line>
                                <line x1="9" y1="3" x2="9" y2="21"></line>
                                <line x1="15" y1="3" x2="15" y2="21"></line>
                            </svg>
                        </div>
                        <h3 class="feature-title">Automated Reporting</h3>
                        <p class="feature-description">Generate customized reports for compliance and performance analysis with just a few clicks.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Compliance Integration</h3>
                        <p class="feature-description">Integration with regulatory compliance tools for automated reporting and audit readiness.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Benefits Section -->
        <section id="benefits" class="benefits">
            <div class="container">
                <div class="benefits-grid">
                    <div class="benefits-content">
                        <h2 class="benefits-title">Transforming Operations at GSECL</h2>
                        <p class="benefits-description">
                            The Daily Work Logbook System eliminates paper-based logs and fragmented digital tools, bringing numerous benefits to your operations.
                        </p>
                        
                        <div class="benefits-list">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="benefit-text">
                                    <h3 class="benefit-title">Enhanced Operational Transparency</h3>
                                    <p class="benefit-description">
                                        Real-time visibility into plant operations across all shifts and units.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg> -->
                                </div>
                                
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="benefit-text">
                                    <h3 class="benefit-title">Improved Decision Making</h3>
                                    <p class="benefit-description">
                                        Data-driven insights for better operational and maintenance decisions.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="benefit-text">
                                    <h3 class="benefit-title">Reduced Administrative Burden</h3>
                                    <p class="benefit-description">
                                        Automation of routine logging tasks frees up staff for more critical responsibilities.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="benefits-image-container">
                        <div class="benefits-image-bg"></div>
                        <img src="https://th.bing.com/th/id/OIP.73KKjs_8-RpRbWKCp4VacQHaEx?rs=1&pid=ImgDetMain"
                        alt="GSECL Power Plant Operations" class="benefits-image">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Role-Based Benefits -->
        <section class="roles">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Benefits for Every Role</h2>
                    <p class="section-description">
                        Our system is designed to meet the specific needs of different roles within your organization.
                    </p>
                </div>
                
                <div class="roles-grid">
                    <div class="role-card">
                        <div class="role-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="role-title">Shift Engineers</h3>
                        <ul class="role-benefits">
                            <li>Simplified data entry with predefined templates</li>
                            <li>Quick access to equipment history and parameters</li>
                            <li>Seamless shift handover process</li>
                            <li>Mobile access for on-the-go logging</li>
                            <li>Instant notifications for critical events</li>
                        </ul>
                    </div>
                    
                    <div class="role-card">
                        <div class="role-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="3" y1="15" x2="21" y2="15"></line>
                                <line x1="9" y1="3" x2="9" y2="21"></line>
                                <line x1="15" y1="3" x2="15" y2="21"></line>
                            </svg>
                        </div>
                        <h3 class="role-title">Plant Supervisors</h3>
                        <ul class="role-benefits">
                            <li>Comprehensive overview of plant operations</li>
                            <li>Real-time monitoring of all equipment status</li>
                            <li>Performance analytics and trend identification</li>
                            <li>Resource allocation optimization</li>
                            <li>Maintenance scheduling and tracking</li>
                        </ul>
                    </div>
                    
                    <div class="role-card">
                        <div class="role-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <h3 class="role-title">Compliance Teams</h3>
                        <ul class="role-benefits">
                            <li>Automated compliance report generation</li>
                            <li>Audit-ready documentation at all times</li>
                            <li>Regulatory requirement tracking</li>
                            <li>Historical data archiving and retrieval</li>
                            <li>Evidence-based compliance verification</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main> 
    <div class="widgets-section">
        <div class="row clearfix">
            <div class="footer-column col-md-3 col-sm-6 col-xs-12"><div id="bunch_about_us-2" class="footer-widget widget_bunch_about_us">      		
    <div class="logo-widget">
    <div class="widget-content">
        <!--div class="logo-box">
            <a href="https://www.gsecl.in/"><img src="" alt="" /></a>
        </div-->
        
                                <h2> Additional Content </h2>
                 <div class="text"><ul id="footer_important_info" class="menu">
    <li class="important_info_li"><a href="https://www.gsecl.in/wp-content/uploads/2020/07/Circular for NOVEL CORONA VIRUS-2019.pdf" target="_blank">Circular for NOVEL CORONA VIRUS-2019</a></li>
    <li class="important_info_li"><a href="https://www.gsecl.in/wp-content/uploads/2020/07/Additional Circular_NOVEL CORONA VIRUS-2019 (Covid-19).pdf" target="_blank">Additional Circular_NOVEL CORONA VIRUS-2019 (Covid-19)</a></li>
    <li class="important_info_li"><a href="https://www.gsecl.in/wp-content/uploads/2020/07/Corona Circular.pdf" target="_blank">Corona Circular</a></li>
    <li class="important_info_li"><a href="https://www.gsecl.in/gsecl-petition-for-true-up/" target="_blank">GSECL Petition for True-up for FY 22.23 &amp; Tariff Determination for FY 24-25</a></li>
    </ul></div>
                            <div class="email"></div>
        
                                                    
                        </div>
    </div>
    
    </div></div><div class="footer-column col-md-3 col-sm-6 col-xs-12"><div id="text_icl-2" class="footer-widget widget_text_icl"><h2>GSECL</h2>		<div class="textwidget"><div class="menu-footer-company-menu-container gsecl_footer_menu">
    <ul id="menu-footer-company-menu" class="menu">		
    <li id="menu-item-840" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-840"><a href="https://www.gsecl.in/rti/">RTI</a></li>
    <li id="menu-item-852" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-852"><a href="https://www.gsecl.in/career/">Career</a></li>
    <li id="menu-item-867" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-867"><a href="https://www.gsecl.in/contact/">Contact Us</a></li>
    <li id="menu-item-849" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-849"><a href="https://www.gsecl.in/sitemap/">Sitemap</a></li>
    </ul>
    <div class="main_contact_info"><span class="footer_gst_info">GSECL’s GST Registration No. / <span class="provisional_footer">Provisional ID
    <strong>24AAACG6864F1ZO</strong></span></span>
    <b class="footer_main_contact_info">CONTACT INFO</b>
    <strong class="footer_mail_info">Email : <a href="mailto:gsecl@gebmail.com" target="_top">gsecl@gebmail.com</a></strong></div>
    </div></div>
    </div></div><div class="footer-column col-md-3 col-sm-6 col-xs-12"><div id="text_icl-3" class="footer-widget widget_text_icl"><h2>IMPORTANT LINKS</h2>		<div class="textwidget">
    <div class="marquee">
  
  
    
    <div style="padding:3px;"><marquee style="height:100px;" scrollamount="2" scrolldelay="5" direction="up" onmouseover="this.stop()" onmouseout="this.start()"><a href="http://powermin.nic.in" target="_blank" rel="noopener">MINISTRY OF POWER</a>   <br><br>   <a href="http://www.gujaratindia.com/" target="_blank" rel="noopener">GOVERNMENT OF GUJARAT</a>   <br><br>   <a href="http://guj-epd.gov.in/" target="_blank" rel="noopener">ENERGY &amp; PETROCHEMICALS DEPARTMENT </a>   <br><br>   <a href="http://www.gercin.org" target="_blank" rel="noopener">GUJARAT ELECTRICITY REGULATORY COMMISION</a>   <br><br>   <a href="http://geda.gujarat.gov.in" target="_blank" rel="noopener">GUJARAT ENERGY DEVELOPMEN AGENCY</a>   <br><br>   <a href="http://www.ntpc.co.in/" target="_blank" rel="noopener">NTPC Ltd.</a>   <br><br>   <a href="http://www.guvnl.com/guvnl/index.aspx" target="_blank" rel="noopener">G U V N L</a>   <br><br>   <a href="http://getco.co.in/getco_new/" target="_blank" rel="noopener">G E T C O </a>   <br><br>   <a href="http://www.mgvcl.com/" target="_blank" rel="noopener">M G V C L</a>   <br><br>   <a href="http://www.dgvcl.com/dgvclweb/index.php" target="_blank" rel="noopener">D G V C L</a>   <br><br>   <a href="http://www.pgvcl.com/" target="_blank" rel="noopener"> P G V C L </a>   <br><br>   <a href="http://www.ugvcl.com/" target="_blank" rel="noopener"> U G V C L </a></marquee></div></div>
    </div></div><div class="footer-column col-md-3 col-sm-6 col-xs-12"><div id="text_icl-4" class="footer-widget widget_text_icl"><h2>About</h2>		<div class="textwidget">The Company was promoted by erstwhile Gujarat Electricity Board (GEB) as it’s wholly owned subsidiary in the context of liberalization and as a part of efforts towards restructuring of the Power Sector.
    <img class="footer_img" src="https://www.gsecl.in/wp-content/uploads/2018/12/footer_img2-300x300.jpg" alt=""></div>
    </div></div>                                                <script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js"></script>
        </div>
    </div>
    
</body>
</html>
