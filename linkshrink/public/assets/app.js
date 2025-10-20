        let linkCounter = 0;
        let totalClicks = 0;
        const links = [];

        document.getElementById('urlForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const urlInput = document.getElementById('urlInput');
            const originalUrl = urlInput.value.trim();
            
            if (!originalUrl) return;
            
            // Generate short URL
            const shortCode = generateShortCode();
            const shortUrl = `https://lnk.sh/${shortCode}`;
            
            // Store link data
            const linkData = {
                id: Date.now(),
                original: originalUrl,
                short: shortUrl,
                clicks: 0,
                created: new Date().toLocaleString('es-ES')
            };
            
            links.unshift(linkData);
            linkCounter++;
            
            // Update UI
            updateStats();
            showResult(shortUrl);
            updateRecentLinks();
            
            // Clear input
            urlInput.value = '';
        });

        document.getElementById('copyBtn').addEventListener('click', function() {
            const shortUrl = document.getElementById('shortUrl');
            shortUrl.select();
            document.execCommand('copy');
            
            // Visual feedback
            const btn = this;
            const originalText = btn.textContent;
            btn.textContent = '¡Copiado!';
            btn.classList.add('bg-green-500');
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('bg-green-500');
            }, 2000);
        });

        function generateShortCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < 6; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }

        function showResult(shortUrl) {
            document.getElementById('shortUrl').value = shortUrl;
            document.getElementById('resultSection').classList.remove('hidden');
        }

        function updateStats() {
            document.getElementById('totalLinks').textContent = linkCounter;
            document.getElementById('totalClicks').textContent = totalClicks;
        }

        function updateRecentLinks() {
            const recentSection = document.getElementById('recentLinks');
            const linksList = document.getElementById('linksList');
            
            if (links.length > 0) {
                recentSection.classList.remove('hidden');
                
                linksList.innerHTML = links.slice(0, 5).map(link => `
                    <div class="glass-effect rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm text-blue-300 truncate">${link.short}</div>
                                <div class="text-xs text-gray-400 truncate">${link.original}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-purple-400">${link.clicks} clics</div>
                                <div class="text-xs text-gray-500">${link.created}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }
