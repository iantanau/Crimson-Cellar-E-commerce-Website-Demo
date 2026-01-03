<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

	<!-- Contact Content -->
    <div class="contact-container">
        <div class="contact-header">
            <h1 class="contact-title">Get In Touch</h1>
            <p class="contact-subtitle">Have questions about our wines or need assistance with an order? We're here to help.</p>
        </div>
        
        <div class="contact-content">
            <div class="contact-info">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-details">
                        <h3>Visit Us</h3>
                        <p>120 Currie Street<br>Adelaide, SA 5000<br>Australia</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-details">
                        <h3>Call Us</h3>
                        <p>Phone: 08 0123 4567<br>Fax: 08 0123 4567<br>Mon-Fri: 9am - 6pm ACST</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-details">
                        <h3>Email Us</h3>
                        <p>General: info@crimsoncellar.com<br>Support: support@crimsoncellar.com<br>Sales: sales@crimsoncellar.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-details">
                        <h3>Store Hours</h3>
                        <p>Mon-Sat: 10AM - 7PM</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h2 class="form-title">Send Us a Message</h2>
                <form>
                    <div class="form-group">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Enter your name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="What is this regarding?">
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea id="message" name="message" class="form-textarea" placeholder="How can we help you?" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
        
        <div class="map-container">
			<iframe 
			  src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3151.843821311513!2d138.600739!3d-34.928498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sau!4v1693981000000!5m2!1sen!2sau" 
			  width="600" 
			  height="450" 
			  style="border:0;" 
			  allowfullscreen="" 
			  loading="lazy" 
			  referrerpolicy="no-referrer-when-downgrade">
			</iframe>        
		</div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>