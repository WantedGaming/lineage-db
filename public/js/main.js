/**
 * Main JavaScript file for LineageII Remastered Database
 */
 
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Boss Timer Functionality
    initBossTimers();
    
    // Map functionality
    initMaps();
    
    // Enchant Simulator
    initEnchantSimulator();
    
    // Back to top button
    addBackToTopButton();
});

/**
 * Initialize boss timer countdowns
 */
function initBossTimers() {
    const bossTimers = document.querySelectorAll('.boss-spawn-timer');
    
    if (bossTimers.length === 0) return;
    
    bossTimers.forEach(timer => {
        const spawnTime = timer.getAttribute('data-spawn-time');
        if (!spawnTime) return;
        
        // Update the timer every second
        updateBossTimer(timer, spawnTime);
        setInterval(() => updateBossTimer(timer, spawnTime), 1000);
    });
}

/**
 * Update a boss timer element
 */
function updateBossTimer(timerElement, spawnTimeStr) {
    const now = new Date();
    const spawnTime = new Date(spawnTimeStr);
    const timeDiff = spawnTime - now;
    
    if (timeDiff <= 0) {
        timerElement.innerHTML = 'Spawned!';
        timerElement.classList.add('boss-spawned');
        return;
    }
    
    // Calculate hours, minutes, seconds
    const hours = Math.floor(timeDiff / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
    
    // Format the time
    const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    timerElement.innerHTML = timeString;
    
    // Add warning class if spawn is soon (less than 10 minutes)
    if (timeDiff < 10 * 60 * 1000) {
        timerElement.classList.add('boss-spawning-soon');
    } else {
        timerElement.classList.remove('boss-spawning-soon');
    }
}

/**
 * Initialize maps with interactive points
 */
function initMaps() {
    const mapContainers = document.querySelectorAll('.map-wrapper');
    
    if (mapContainers.length === 0) return;
    
    mapContainers.forEach(container => {
        const mapPoints = container.querySelectorAll('.map-point');
        
        mapPoints.forEach(point => {
            point.addEventListener('click', function() {
                const pointId = this.getAttribute('data-id');
                const pointType = this.getAttribute('data-type');
                const pointName = this.getAttribute('data-name');
                
                if (pointType === 'npc') {
                    window.location.href = `${SITE_URL}/pages/npcs/view.php?id=${pointId}`;
                } else if (pointType === 'spawn') {
                    window.location.href = `${SITE_URL}/pages/spawns/view.php?id=${pointId}`;
                }
            });
            
            // Add tooltip
            const pointName = point.getAttribute('data-name');
            if (pointName) {
                new bootstrap.Tooltip(point, {
                    title: pointName,
                    placement: 'top'
                });
            }
        });
    });
}

/**
 * Initialize enchant simulator
 */
function initEnchantSimulator() {
    const enchantSimulator = document.getElementById('enchant-simulator');
    
    if (!enchantSimulator) return;
    
    const enchantButton = document.getElementById('enchant-button');
    const itemSelect = document.getElementById('item-select');
    const currentEnchant = document.getElementById('current-enchant');
    const resultDisplay = document.getElementById('enchant-result');
    const enchantHistory = document.getElementById('enchant-history');
    
    if (!enchantButton || !itemSelect || !currentEnchant || !resultDisplay || !enchantHistory) return;
    
    enchantButton.addEventListener('click', function() {
        const selectedItem = itemSelect.value;
        const enchantLevel = parseInt(currentEnchant.textContent);
        
        // Simulate enchant (this would need real probabilities from your database)
        const success = simulateEnchant(selectedItem, enchantLevel);
        
        if (success) {
            currentEnchant.textContent = enchantLevel + 1;
            resultDisplay.innerHTML = `<div class="alert alert-success">Success! Your item is now +${enchantLevel + 1}</div>`;
            
            // Add to history
            const historyEntry = document.createElement('li');
            historyEntry.className = 'list-group-item text-success';
            historyEntry.textContent = `+${enchantLevel} → +${enchantLevel + 1}: Success!`;
            enchantHistory.prepend(historyEntry);
        } else {
            // If failed, determine if item breaks (based on safe enchant)
            const safeEnchant = parseInt(itemSelect.options[itemSelect.selectedIndex].getAttribute('data-safe-enchant'));
            
            if (enchantLevel > safeEnchant) {
                // Item breaks
                currentEnchant.textContent = '0';
                resultDisplay.innerHTML = `<div class="alert alert-danger">Failed! Your item has been destroyed.</div>`;
                
                // Add to history
                const historyEntry = document.createElement('li');
                historyEntry.className = 'list-group-item text-danger';
                historyEntry.textContent = `+${enchantLevel} → Destroyed: Failed catastrophically!`;
                enchantHistory.prepend(historyEntry);
            } else {
                // Item doesn't break, but enchant level decreases
                currentEnchant.textContent = Math.max(0, enchantLevel - 1);
                resultDisplay.innerHTML = `<div class="alert alert-warning">Failed! Your item is now +${Math.max(0, enchantLevel - 1)}</div>`;
                
                // Add to history
                const historyEntry = document.createElement('li');
                historyEntry.className = 'list-group-item text-warning';
                historyEntry.textContent = `+${enchantLevel} → +${Math.max(0, enchantLevel - 1)}: Failed!`;
                enchantHistory.prepend(historyEntry);
            }
        }
    });
}

/**
 * Simulate an enchant attempt
 * This is a placeholder - real implementation would use actual probabilities from your database
 */
function simulateEnchant(itemId, currentEnchant) {
    // Simple probability simulation based on current enchant level
    // In a real implementation, you'd want to get these values from your database
    let successProbability;
    
    if (currentEnchant <= 3) {
        successProbability = 100; // 100% success for +0 to +3
    } else if (currentEnchant <= 6) {
        successProbability = 60; // 60% success for +4 to +6
    } else if (currentEnchant <= 9) {
        successProbability = 40; // 40% success for +7 to +9
    } else if (currentEnchant <= 12) {
        successProbability = 20; // 20% success for +10 to +12
    } else if (currentEnchant <= 15) {
        successProbability = 10; // 10% success for +13 to +15
    } else {
        successProbability = 5; // 5% success for +16 and beyond
    }
    
    // Random number between 1 and 100
    const roll = Math.floor(Math.random() * 100) + 1;
    
    return roll <= successProbability;
}

/**
 * Add a back-to-top button that appears when scrolling down
 */
function addBackToTopButton() {
    // Create the button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.setAttribute('title', 'Back to top');
    backToTopBtn.setAttribute('id', 'back-to-top-btn');
    backToTopBtn.style.position = 'fixed';
    backToTopBtn.style.bottom = '20px';
    backToTopBtn.style.right = '20px';
    backToTopBtn.style.display = 'none';
    backToTopBtn.style.zIndex = '99';
    backToTopBtn.style.border = 'none';
    backToTopBtn.style.outline = 'none';
    backToTopBtn.style.backgroundColor = '#007bff';
    backToTopBtn.style.color = 'white';
    backToTopBtn.style.cursor = 'pointer';
    backToTopBtn.style.padding = '10px 15px';
    backToTopBtn.style.borderRadius = '50%';
    
    document.body.appendChild(backToTopBtn);
    
    // Show/hide the button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Scroll to top when button is clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Function to highlight search terms in results
 */
function highlightSearchTerms() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchTerm = urlParams.get('q');
    
    if (!searchTerm) return;
    
    const searchTermLower = searchTerm.toLowerCase();
    const searchElements = document.querySelectorAll('.search-content');
    
    searchElements.forEach(element => {
        const originalText = element.innerHTML;
        const lowerText = originalText.toLowerCase();
        
        let newText = originalText;
        let lastIndex = 0;
        let index = lowerText.indexOf(searchTermLower, lastIndex);
        
        while (index !== -1) {
            const matchedText = originalText.substring(index, index + searchTerm.length);
            const replacement = `<span class="search-highlight">${matchedText}</span>`;
            
            newText = newText.substring(0, index) + replacement + newText.substring(index + searchTerm.length);
            
            // Adjust lastIndex to account for the added HTML
            lastIndex = index + replacement.length;
            // Find the next occurrence
            index = lowerText.indexOf(searchTermLower, index + searchTerm.length);
        }
        
        element.innerHTML = newText;
    });
}

// Call search term highlighting if we're on search results page
if (window.location.href.includes('/search.php')) {
    highlightSearchTerms();
}
'