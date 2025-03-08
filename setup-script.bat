@echo off
echo Creating directory structure for LineageII Remastered Database website...
echo.

:: Create main directories
mkdir config
mkdir includes
mkdir includes\core
mkdir includes\models
mkdir includes\templates
mkdir public
mkdir public\css
mkdir public\js
mkdir public\images
mkdir public\images\items
mkdir public\images\npcs
mkdir public\images\skills
mkdir public\images\classes
mkdir public\images\maps
mkdir pages
mkdir pages\items
mkdir pages\npcs
mkdir pages\skills
mkdir pages\spawns
mkdir pages\tools
mkdir admin
mkdir admin\items
mkdir admin\npcs
mkdir admin\skills
mkdir admin\spawns
mkdir admin\drops

echo.
echo Directory structure created successfully!
echo.
echo The following directories have been created:
echo.
echo config
echo includes\core
echo includes\models
echo includes\templates
echo public\css
echo public\js
echo public\images\items
echo public\images\npcs
echo public\images\skills
echo public\images\classes
echo public\images\maps
echo pages\items
echo pages\npcs
echo pages\skills
echo pages\spawns
echo pages\tools
echo admin\items
echo admin\npcs
echo admin\skills
echo admin\spawns
echo admin\drops
echo.
echo Next steps:
echo 1. Place your PHP files in their respective directories
echo 2. Configure config\config.php with your database credentials
echo 3. Upload item and NPC images to their respective directories
echo.
pause
