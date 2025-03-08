</main>
    
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>LineageII Remastered Database</h5>
                    <p>A comprehensive database for LineageII Remastered game.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>/pages/items/" class="text-light">Items</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/npcs/" class="text-light">NPCs</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/skills/" class="text-light">Skills</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/spawns/" class="text-light">Spawns</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Resources</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>/pages/bosses.php" class="text-light">Boss Timers</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/maps.php" class="text-light">Maps</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/quests/" class="text-light">Quests</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/drops.php" class="text-light">Drop Calculator</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> LineageII Remastered Database. All game content and materials are trademarks and copyrights of their respective owners.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/public/js/main.js"></script>
    
    <?php if (isset($extraJs)): ?>
    <?php foreach ($extraJs as $js): ?>
    <script src="<?php echo SITE_URL; ?>/public/js/<?php echo $js; ?>"></script>
    <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>