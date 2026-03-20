<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="cr-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="cr-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
        <div class="score-box"><span class="score-num" id="cr-timer">30</span><span class="score-label">Time</span></div>
    </div>
    <button class="btn-game btn-game-primary" id="cr-start">▶ Start</button>
</div>
<div id="cr-game" style="display:none;text-align:center">
    <p style="color:var(--text-muted);font-size:15px;margin-bottom:10px">Click <strong id="cr-target-word" style="font-size:22px"></strong></p>
    <div id="cr-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;max-width:440px;margin:0 auto"></div>
</div>
<div id="cr-over" class="text-center" style="display:none">
    <div style="font-size:60px">🎨</div><h3 class="section-title">Game Over!</h3>
    <p style="color:var(--text-muted)" class="mb-3">Score: <strong id="cr-final" style="color:var(--accent-green)"></strong></p>
    <button class="btn-game btn-game-primary" id="cr-restart">Play Again</button>
</div>
<script>
(function(){
    const DIFF={Easy:{time:45,lives:5,grid:8},Medium:{time:30,lives:3,grid:8},Hard:{time:20,lives:2,grid:12}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {time:totalTime,lives:maxLives,grid:gridSize}=DIFF[diff]||DIFF.Medium;
    const cols=Math.min(4,gridSize/2);
    const colors=['Red','Blue','Green','Yellow','Purple','Orange'];
    const colorMap={Red:'#ff3355',Blue:'#0066ff',Green:'#38b000',Yellow:'#f7cc00',Purple:'#9b5de5',Orange:'#f7931e'};
    let score=0,lives=maxLives,timer,timeLeft=totalTime,active=false;
    function updateLives(){document.getElementById('cr-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function start(){
        document.getElementById('cr-start').style.display='none';document.getElementById('cr-over').style.display='none';document.getElementById('cr-game').style.display='block';
        document.getElementById('cr-grid').style.gridTemplateColumns=`repeat(${cols},1fr)`;
        score=0;lives=maxLives;timeLeft=totalTime;active=true;updateLives();
        document.getElementById('cr-score').textContent=0;document.getElementById('cr-timer').textContent=totalTime;
        clearInterval(timer);timer=setInterval(()=>{timeLeft--;document.getElementById('cr-timer').textContent=timeLeft;if(timeLeft<=0)endGame();},1000);
        nextRound();
    }
    function nextRound(){
        if(!active)return;
        const target=colors[Math.floor(Math.random()*colors.length)];
        document.getElementById('cr-target-word').textContent=target;document.getElementById('cr-target-word').style.color=colorMap[target];
        const picks=[target,...colors.filter(c=>c!==target).sort(()=>Math.random()-.5).slice(0,gridSize-1)].sort(()=>Math.random()-.5);
        const grid=document.getElementById('cr-grid');grid.innerHTML='';
        picks.forEach(c=>{
            const btn=document.createElement('button');
            btn.style.cssText=`padding:20px 0;border-radius:12px;border:2px solid ${colorMap[c]}40;background:${colorMap[c]}20;color:${colorMap[c]};font-family:var(--font-display);font-size:14px;font-weight:700;cursor:pointer;transition:all 0.15s`;
            btn.textContent=c;
            btn.onclick=()=>{if(!active)return;if(c===target){score+=10;document.getElementById('cr-score').textContent=score;}else{lives--;updateLives();if(lives<=0){endGame();return;}}nextRound();};
            grid.appendChild(btn);
        });
    }
    function endGame(){
        active=false;clearInterval(timer);document.getElementById('cr-game').style.display='none';
        document.getElementById('cr-final').textContent=score;document.getElementById('cr-over').style.display='block';
        if(typeof submitScore==='function')submitScore(score);
    }
    document.getElementById('cr-start').addEventListener('click',start);
    document.getElementById('cr-restart').addEventListener('click',start);
})();
</script>
