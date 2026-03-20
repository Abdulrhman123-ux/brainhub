<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="dcr-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="dcr-streak">0</span><span class="score-label">Streak</span></div>
        <div class="score-box"><span class="score-num" id="dcr-speed">-</span><span class="score-label">Speed</span></div>
    </div>
    <p style="color:var(--text-muted);font-size:15px;margin-bottom:16px">Click <strong style="color:var(--accent-green)">GREEN</strong> only. One red click = game over!</p>
    <button class="btn-game btn-game-primary" id="dcr-start">▶ Start</button>
</div>
<div id="dcr-grid" style="display:none;gap:10px;max-width:440px;margin:0 auto"></div>
<div id="dcr-over" class="text-center" style="display:none">
    <div style="font-size:60px">💥</div>
    <h3 class="section-title" style="color:var(--accent-pink)">You Clicked Red!</h3>
    <p style="color:var(--text-muted)" class="mb-3">Score: <strong id="dcr-final" style="color:var(--accent-green)"></strong> | Streak: <strong id="dcr-final-streak" style="color:var(--accent-cyan)"></strong></p>
    <button class="btn-game btn-game-primary" id="dcr-restart">Try Again</button>
</div>
<script>
(function(){
    const DIFF={Easy:{cols:4,rows:3,startSpeed:1400,redMin:1,redMax:2},Medium:{cols:5,rows:4,startSpeed:1000,redMin:2,redMax:3},Hard:{cols:6,rows:4,startSpeed:700,redMin:3,redMax:4}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {cols,rows,startSpeed,redMin,redMax}=DIFF[diff]||DIFF.Medium;
    let score=0,streak=0,bestStreak=0,interval,speed=startSpeed,active=false;
    function start(){
        document.getElementById('dcr-start').style.display='none';document.getElementById('dcr-over').style.display='none';
        const grid=document.getElementById('dcr-grid');grid.style.cssText=`display:grid;grid-template-columns:repeat(${cols},1fr);gap:10px;max-width:${cols*76}px;margin:0 auto`;
        score=0;streak=0;bestStreak=0;speed=startSpeed;active=true;updateUI();clearInterval(interval);spawnTiles();interval=setInterval(spawnTiles,speed);
    }
    function updateUI(){document.getElementById('dcr-score').textContent=score;document.getElementById('dcr-streak').textContent=streak;document.getElementById('dcr-speed').textContent=(speed/1000).toFixed(1)+'s';}
    function spawnTiles(){
        if(!active)return;
        const grid=document.getElementById('dcr-grid');grid.innerHTML='';
        const total=cols*rows;const rc=redMin+Math.floor(Math.random()*(redMax-redMin+1));
        const types=Array(total-rc).fill('green').concat(Array(rc).fill('red')).sort(()=>Math.random()-.5);
        types.forEach(type=>{
            const tile=document.createElement('div');
            tile.style.cssText=`aspect-ratio:1;border-radius:12px;cursor:pointer;transition:transform 0.1s;border:2px solid ${type==='green'?'rgba(56,176,0,0.4)':'rgba(255,0,80,0.3)'};background:${type==='green'?'linear-gradient(135deg,#226600,#38b000)':'linear-gradient(135deg,#660000,#cc0033)'};box-shadow:0 0 10px ${type==='green'?'rgba(56,176,0,0.3)':'rgba(255,0,80,0.3)'}`;
            tile.onmouseenter=()=>tile.style.transform='scale(0.95)';tile.onmouseleave=()=>tile.style.transform='scale(1)';
            tile.onclick=()=>{
                if(!active)return;
                if(type==='green'){
                    score+=streak>=5?2:1;streak++;if(streak>bestStreak)bestStreak=streak;
                    if(streak>0&&streak%5===0){clearInterval(interval);speed=Math.max(300,speed-80);interval=setInterval(spawnTiles,speed);}
                    updateUI();
                } else {
                    active=false;clearInterval(interval);tile.style.background='linear-gradient(135deg,#ff0000,#ff6666)';tile.style.transform='scale(1.2)';tile.style.boxShadow='0 0 40px rgba(255,0,0,0.9)';
                    setTimeout(()=>{grid.style.display='none';document.getElementById('dcr-final').textContent=score;document.getElementById('dcr-final-streak').textContent=bestStreak;document.getElementById('dcr-over').style.display='block';if(typeof submitScore==='function')submitScore(score);},500);
                }
            };
            grid.appendChild(tile);
        });
    }
    document.getElementById('dcr-start').addEventListener('click',start);
    document.getElementById('dcr-restart').addEventListener('click',start);
})();
</script>
