<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="pr-level">1</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="pr-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="pr-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="pr-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Watch the pattern, then repeat it!</div>
    <button class="btn-game btn-game-primary" id="pr-start">▶ Start</button>
</div>
<div id="pr-board" style="display:none;max-width:320px;margin:0 auto">
    <div id="pr-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px"></div>
</div>
<script>
(function(){
    const DIFF={Easy:{startCount:2,flashSpeed:700,lives:3},Medium:{startCount:3,flashSpeed:600,lives:3},Hard:{startCount:4,flashSpeed:450,lives:2}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {startCount,flashSpeed,lives:maxLives}=DIFF[diff]||DIFF.Medium;
    const COLS=['#6c63ff','#f7931e','#00b4d8','#38b000','#ff006e','#ffd700','#9b5de5','#ff6b35','#00ccff'];
    let level=1,score=0,lives=maxLives,pattern=[],playerIdx=0,showing=false,gameActive=false;
    function updateLives(){document.getElementById('pr-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('pr-status');el.textContent=t;el.style.color=c;}
    function start(){document.getElementById('pr-start').style.display='none';level=1;score=0;lives=maxLives;document.getElementById('pr-level').textContent=1;document.getElementById('pr-score').textContent=0;updateLives();buildBoard();setTimeout(nextRound,300);}
    function buildBoard(){const board=document.getElementById('pr-board');board.style.display='block';const grid=document.getElementById('pr-grid');grid.innerHTML='';for(let i=0;i<9;i++){const cell=document.createElement('div');cell.style.cssText='aspect-ratio:1;border-radius:12px;cursor:pointer;background:var(--bg-card2);border:2px solid var(--border);transition:all 0.15s;';cell.dataset.idx=i;cell.onclick=()=>playerClick(i,cell);grid.appendChild(cell);}}
    function nextRound(){
        const count=Math.min(startCount+level-1,9);pattern=[];
        while(pattern.length<count){const r=Math.floor(Math.random()*9);if(!pattern.includes(r))pattern.push(r);}
        playerIdx=0;showing=true;gameActive=false;setStatus('👀 Watch the pattern!','var(--accent-cyan)');showPattern();
    }
    function showPattern(){
        const cells=document.querySelectorAll('#pr-grid div');let i=0;
        const interval=setInterval(()=>{
            if(i>0){cells[pattern[i-1]].style.cssText='aspect-ratio:1;border-radius:12px;cursor:pointer;background:var(--bg-card2);border:2px solid var(--border);transition:all 0.15s';}
            if(i<pattern.length){const col=COLS[pattern[i]];cells[pattern[i]].style.cssText=`aspect-ratio:1;border-radius:12px;cursor:pointer;background:${col};border:2px solid ${col};box-shadow:0 0 20px ${col}80;transition:all 0.15s`;i++;}
            else{clearInterval(interval);setTimeout(()=>{cells.forEach(c=>c.style.cssText='aspect-ratio:1;border-radius:12px;cursor:pointer;background:var(--bg-card2);border:2px solid var(--border);transition:all 0.15s');showing=false;gameActive=true;setStatus('🎯 Repeat the pattern!','var(--accent-orange)');},500);}
        },flashSpeed);
    }
    function playerClick(idx,cell){
        if(!gameActive||showing)return;
        const expected=pattern[playerIdx];
        if(idx===expected){
            const col=COLS[idx];cell.style.background=col;cell.style.borderColor=col;cell.style.boxShadow=`0 0 16px ${col}60`;
            setTimeout(()=>{cell.style.background='var(--bg-card2)';cell.style.borderColor='var(--border)';cell.style.boxShadow='none';},280);
            playerIdx++;
            if(playerIdx===pattern.length){
                gameActive=false;score+=level*10;document.getElementById('pr-score').textContent=score;level++;document.getElementById('pr-level').textContent=level;
                setStatus('✅ Correct!','var(--accent-green)');setTimeout(nextRound,900);
            }
        } else {
            gameActive=false;cell.style.background='var(--accent-pink)';cell.style.borderColor='var(--accent-pink)';
            setTimeout(()=>{cell.style.background='var(--bg-card2)';cell.style.borderColor='var(--border)';},400);
            lives--;updateLives();
            if(lives<=0){
                setStatus(`❌ Game Over! Score: ${score}`,'var(--accent-pink)');
                if(typeof submitScore==='function')submitScore(score);
                setTimeout(()=>{document.getElementById('pr-board').style.display='none';document.getElementById('pr-start').textContent='Play Again';document.getElementById('pr-start').style.display='inline-block';level=1;score=0;lives=maxLives;document.getElementById('pr-level').textContent=1;document.getElementById('pr-score').textContent=0;updateLives();setStatus('Watch the pattern, then repeat it!');},1500);
            } else {setStatus(`❌ Wrong! ${lives} lives left`,'var(--accent-pink)');setTimeout(nextRound,1200);}
        }
    }
    document.getElementById('pr-start').addEventListener('click',start);
})();
</script>
