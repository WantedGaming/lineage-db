<?php
// Include configuration files
require_once '../../config/config.php';
require_once '../../config/constants.php';

// Include database connection
require_once '../../includes/core/Database.php';

// Include helper functions
require_once '../../includes/functions.php';

// Include models
require_once '../../includes/models/NPC.php';

// Initialize models
$npcModel = new NPC();

// Get boss spawn times
$bossSpawns = $npcModel->getBossSpawnTimes();

// Group bosses by map
$bossesByMap = [];
foreach ($bossSpawns as $boss) {
    $mapName = $boss['map_name'] ?? 'Unknown';
    if (!isset($bossesByMap[$mapName])) {
        $bossesByMap[$mapName] = [];
    }
    $bossesByMap[$mapName][] = $boss;
}

// Sort maps alphabetically
ksort($bossesByMap);

// Page title
$pageTitle = "Boss Timer";

// Extra CSS and JS
$extraCss = ['boss_timer.css'];
$extraJs = ['boss_timer.js'];

// Include header template
include_once '../../includes/templates/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Boss Spawn Timers</h5>
            </div>
            <div class="card-body">
                <p class="alert alert-info">
                    <i class="fas fa-info-circle"></i> This page shows estimated spawn times for boss monsters. 
                    The actual spawn time may vary depending on server settings and random factors.
                </p>
                
                <!-- Boss Filter Controls -->
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" id="boss-search" class="form-control" placeholder="Search boss...">
                        </div>
                        <div class="col-md-4">
                            <select id="map-filter" class="form-select">
                                <option value="all">All Maps</option>
                                <?php foreach (array_keys($bossesByMap) as $mapName): ?>
                                <option value="<?php echo htmlspecialchars($mapName); ?>"><?php echo htmlspecialchars($mapName); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="level-filter" class="form-select">
                                <option value="all">All Levels</option>
                                <option value="1-50">Level 1-50</option>
                                <option value="51-70">Level 51-70</option>
                                <option value="71-80">Level 71-80</option>
                                <option value="81+">Level 81+</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Boss Timers by Map -->
                <div id="boss-timers-container">
                    <?php foreach ($bossesByMap as $mapName => $bosses): ?>
                    <div class="boss-map-section mb-4" data-map="<?php echo htmlspecialchars($mapName); ?>">
                        <h4 class="mb-3"><?php echo htmlspecialchars($mapName); ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Boss</th>
                                        <th>Level</th>
                                        <th>Location</th>
                                        <th>Spawn Time</th>
                                        <th>Next Spawn</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bosses as $boss): ?>
                                    <tr class="boss-row" data-boss-name="<?php echo htmlspecialchars($boss['boss_name']); ?>" data-boss-level="<?php echo (int)$boss['level']; ?>">
                                        <td>
                                            <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=<?php echo $boss['npcid']; ?>">
                                                <?php echo $boss['boss_name']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $boss['level']; ?></td>
                                        <td>
                                            <?php echo formatCoordinates($boss['spawnX'], $boss['spawnY']); ?>
                                            <?php if ($boss['rndRange'] > 0): ?>
                                            <span class="text-muted">(±<?php echo $boss['rndRange']; ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            // Parse spawn time - this is a placeholder, adapt to your actual data structure
                                            if (!empty($boss['spawnTime'])) {
                                                echo formatSpawnTime($boss['spawnTime']);
                                            } else {
                                                echo 'Random';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            // Generate placeholder spawn time for demonstration
                                            // In a real implementation, you'd calculate this based on last death time and respawn interval
                                            $placeholderTime = time() + (rand(1, 24) * 3600); // Random time in the next 24 hours
                                            $spawnTime = date('Y-m-d H:i:s', $placeholderTime);
                                            ?>
                                            <span class="boss-spawn-timer" data-spawn-time="<?php echo $spawnTime; ?>">
                                                Calculating...
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=<?php echo $boss['npcid']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                <a href="<?php echo SITE_URL; ?>/pages/maps.php?id=<?php echo $boss['spawnMapId']; ?>&x=<?php echo $boss['spawnX']; ?>&y=<?php echo $boss['spawnY']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success boss-track-btn" data-boss-id="<?php echo $boss['npcid']; ?>" data-boss-name="<?php echo htmlspecialchars($boss['boss_name']); ?>">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- No Results Message -->
                <div id="no-bosses-found" class="alert alert-warning" style="display: none;">
                    <h4 class="alert-heading">No bosses found</h4>
                    <p>No bosses match your current filter settings.</p>
                    <p>Try changing your filters or search terms.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Tracked Bosses -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tracked Bosses</h5>
            </div>
            <div class="card-body">
                <div id="tracked-bosses-container">
                    <div class="alert alert-info">
                        <p>You're not tracking any bosses yet.</p>
                        <p>Click the <i class="fas fa-bell"></i> button next to a boss to start tracking it.</p>
                    </div>
                    
                    <!-- Tracked bosses will be added here via JavaScript -->
                    <ul id="tracked-bosses-list" class="list-group" style="display: none;">
                        <!-- Example tracked boss - will be added dynamically via JS
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Antharas</h6>
                                <div class="small">Next spawn: <span class="boss-spawn-timer" data-spawn-time="2023-05-01 15:30:00">Calculating...</span></div>
                            </div>
                            <button class="btn btn-sm btn-danger boss-untrack-btn" data-boss-id="1"><i class="fas fa-times"></i></button>
                        </li>
                        -->
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Boss Tips -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Boss Hunting Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h6><i class="fas fa-users"></i> Form a Party</h6>
                        <p class="small mb-0">Most bosses require a well-coordinated party to defeat. Make sure to have balanced roles in your party.</p>
                    </li>
                    <li class="list-group-item">
                        <h6><i class="fas fa-flask"></i> Prepare Buffs and Potions</h6>
                        <p class="small mb-0">Stock up on HP/MP potions and make sure to have all necessary buffs before engaging a boss.</p>
                    </li>
                    <li class="list-group-item">
                        <h6><i class="fas fa-search"></i> Scout the Area</h6>
                        <p class="small mb-0">Arrive at the boss location early to secure your spot and clear nearby mobs.</p>
                    </li>
                    <li class="list-group-item">
                        <h6><i class="fas fa-exclamation-triangle"></i> Watch for Special Attacks</h6>
                        <p class="small mb-0">Many bosses have unique skills that can quickly wipe out unprepared parties. Know the boss mechanics before engaging.</p>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Recent Boss Kills -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Recent Boss Kills</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">This information is updated in real-time from server data.</p>
                
                <!-- Placeholder Recent Kills -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Antharas</h6>
                                <div class="small text-muted">Killed by <strong>DragonSlayer Guild</strong></div>
                            </div>
                            <span class="small text-muted">2 hours ago</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Queen Ant</h6>
                                <div class="small text-muted">Killed by <strong>Hero123</strong></div>
                            </div>
                            <span class="small text-muted">5 hours ago</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Core</h6>
                                <div class="small text-muted">Killed by <strong>Conquerors Alliance</strong></div>
                            </div>
                            <span class="small text-muted">Yesterday</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Boss Timer JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const bossSearch = document.getElementById('boss-search');
    const mapFilter = document.getElementById('map-filter');
    const levelFilter = document.getElementById('level-filter');
    const noResultsMessage = document.getElementById('no-bosses-found');
    
    function applyFilters() {
        const searchTerm = bossSearch.value.toLowerCase();
        const selectedMap = mapFilter.value;
        const selectedLevel = levelFilter.value;
        
        // Get all boss sections and rows
        const mapSections = document.querySelectorAll('.boss-map-section');
        const bossRows = document.querySelectorAll('.boss-row');
        
        // Hide all sections initially
        mapSections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Track if any bosses are visible after filtering
        let visibleBosses = 0;
        
        // Filter boss rows
        bossRows.forEach(row => {
            const bossName = row.getAttribute('data-boss-name').toLowerCase();
            const bossLevel = parseInt(row.getAttribute('data-boss-level'));
            const parentSection = row.closest('.boss-map-section');
            const sectionMap = parentSection.getAttribute('data-map');
            
            // Check if boss matches all filters
            let matchesSearch = bossName.includes(searchTerm);
            let matchesMap = selectedMap === 'all' || selectedMap === sectionMap;
            let matchesLevel = true; // Default to true
            
            // Apply level filter
            if (selectedLevel !== 'all') {
                if (selectedLevel === '1-50' && (bossLevel < 1 || bossLevel > 50)) {
                    matchesLevel = false;
                } else if (selectedLevel === '51-70' && (bossLevel < 51 || bossLevel > 70)) {
                    matchesLevel = false;
                } else if (selectedLevel === '71-80' && (bossLevel < 71 || bossLevel > 80)) {
                    matchesLevel = false;
                } else if (selectedLevel === '81+' && bossLevel < 81) {
                    matchesLevel = false;
                }
            }
            
            // Show/hide row based on filters
            if (matchesSearch && matchesMap && matchesLevel) {
                row.style.display = '';
                parentSection.style.display = '';
                visibleBosses++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show "no results" message if no bosses match filters
        if (visibleBosses === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }
    
    // Add event listeners to filter controls
    bossSearch.addEventListener('input', applyFilters);
    mapFilter.addEventListener('change', applyFilters);
    levelFilter.addEventListener('change', applyFilters);
    
    // Boss tracking functionality
    const trackedBossesList = document.getElementById('tracked-bosses-list');
    const trackedBossesInfo = document.querySelector('#tracked-bosses-container .alert-info');
    
    // Load tracked bosses from localStorage
    function loadTrackedBosses() {
        const trackedBosses = JSON.parse(localStorage.getItem('trackedBosses') || '[]');
        
        if (trackedBosses.length > 0) {
            trackedBossesInfo.style.display = 'none';
            trackedBossesList.style.display = 'block';
            trackedBossesList.innerHTML = '';
            
            trackedBosses.forEach(boss => {
                addTrackedBossToUI(boss.id, boss.name, boss.spawnTime);
            });
        } else {
            trackedBossesInfo.style.display = 'block';
            trackedBossesList.style.display = 'none';
        }
    }
    
    // Add tracked boss to UI
    function addTrackedBossToUI(bossId, bossName, spawnTime) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            <div>
                <h6>${bossName}</h6>
                <div class="small">Next spawn: <span class="boss-spawn-timer" data-spawn-time="${spawnTime}">Calculating...</span></div>
            </div>
            <button class="btn btn-sm btn-danger boss-untrack-btn" data-boss-id="${bossId}"><i class="fas fa-times"></i></button>
        `;
        
        trackedBossesList.appendChild(li);
        
        // Reinitialize timer for this element
        const timerElement = li.querySelector('.boss-spawn-timer');
        updateBossTimer(timerElement, spawnTime);
        setInterval(() => updateBossTimer(timerElement, spawnTime), 1000);
    }
    
    // Add event listeners to track/untrack buttons - delegated
    document.addEventListener('click', function(e) {
        // Track button clicked
        if (e.target.closest('.boss-track-btn')) {
            const trackBtn = e.target.closest('.boss-track-btn');
            const bossId = trackBtn.getAttribute('data-boss-id');
            const bossName = trackBtn.getAttribute('data-boss-name');
            
            // Get the spawn time from the corresponding timer
            const bossRow = trackBtn.closest('tr');
            const spawnTime = bossRow.querySelector('.boss-spawn-timer').getAttribute('data-spawn-time');
            
            // Save to localStorage
            const trackedBosses = JSON.parse(localStorage.getItem('trackedBosses') || '[]');
            
            // Check if already tracking
            const alreadyTracking = trackedBosses.some(boss => boss.id === bossId);
            
            if (!alreadyTracking) {
                trackedBosses.push({
                    id: bossId,
                    name: bossName,
                    spawnTime: spawnTime
                });
                
                localStorage.setItem('trackedBosses', JSON.stringify(trackedBosses));
                
                // Update UI
                trackedBossesInfo.style.display = 'none';
                trackedBossesList.style.display = 'block';
                
                addTrackedBossToUI(bossId, bossName, spawnTime);
            }
        }
        
        // Untrack button clicked
        if (e.target.closest('.boss-untrack-btn')) {
            const untrackBtn = e.target.closest('.boss-untrack-btn');
            const bossId = untrackBtn.getAttribute('data-boss-id');
            
            // Remove from localStorage
            let trackedBosses = JSON.parse(localStorage.getItem('trackedBosses') || '[]');
            trackedBosses = trackedBosses.filter(boss => boss.id !== bossId);
            localStorage.setItem('trackedBosses', JSON.stringify(trackedBosses));
            
            // Remove from UI
            untrackBtn.closest('li').remove();
            
            // Show info message if no tracked bosses left
            if (trackedBosses.length === 0) {
                trackedBossesInfo.style.display = 'block';
                trackedBossesList.style.display = 'none';
            }
        }
    });
    
    // Initialize
    loadTrackedBosses();
});
</script>

<?php
// Include footer template
include_once '../../includes/templates/footer.php';
?>