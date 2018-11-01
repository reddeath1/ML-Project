# ML-Project
ML PROJECT


# Quick Start

The following are procedures to follow inorder to get ahead with this project

# Installation

1. See the # clone & download button and click it to download the code
2. Extract the zipped # ML Project folder to your preferable server.
3. Create database name # ml
4. Go to the url pointing this folder
5. Type install e.g (http://yourdomain.com/install
This will install required database table and demo data for this project

# Expectation
    Home
![Alt text](https://github.com/reddeath1/ML-Project/blob/master/assets/images/showcase.PNG?raw=true "showcase")

# Checkout 
![Alt text](https://github.com/reddeath1/ML-Project/blob/master/assets/images/showcase2.PNG?raw=true "showcase")

# Administration area
![Alt text](https://github.com/reddeath1/ML-Project/blob/master/assets/images/showcase1.PNG?raw=true "showcase")

# Technical Details

# File Structure

![Alt text](https://github.com/reddeath1/ML-Project/blob/master/assets/images/showcase3.PNG?raw=true "showcase")

1.  Admin
    -
    This folder hold files/user info with the admin priviledge.
    
2.  Assets
    -
    This hold public files that are seen by the user/client. inside you will see the following folders.
    -  CSS
        -
        This hold css files.
    - Fonts
        -
        This hold fonts files
    - Images
        -
        This hold media files
3.  Config
      -
      This is the configurations folder. all demands that the project needs found on this folder 
      inside there is actual config file that handles those demand.
 
 4. Core
    -
    In this folder hold important files for the project include.
     - Ajx.php 
        -
        This for receiving the ajax call from the client and process them. Home page real time data use this file for it's data.
        
     - Core.php
        -
        This file is responsible for handling the pages and making them user friendly e.g when you type http://yourdomain.com/install, this request is handled by this file.
        
      - Session.php
        -
        Responsible for handling login information and rechecking.
 
 5. Database
    -
    This hold files for database table creation and manipulation.
 6. Views
    -
    This folder hold the pages that are see by the user.
    
 
 # How it's works
 ![Alt text](https://github.com/reddeath1/ML-Project/blob/master/assets/images/database.PNG?raw=true "database")
 
   users table 
   -
   As the image show.
   users hold user information including his/her last presence.
   
   Customer Information
   -
   how customer interact with sys including purchasing
   -
   When the customer save his/her ad on the cart.
   the sys here how the sys do.
   
   Take his/her user id.
   
   Take the ad id 
   
   Dimension id 
   
   Then store them to the cart table. later on then the payment will be issued.
 
   I will not explain on how the ads are store because I did not make a sys for this, as it seen the ads are stored duration the installation process.
   -
   
   
# Change Log

Restructured the internal functions for the images.

Images
- Images where not showing when accessing the page on localhost/ML Project and not domain mydomain.com 