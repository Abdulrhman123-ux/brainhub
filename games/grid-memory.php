<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="gm-level">1</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="gm-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="gm-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="gm-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:16px">Watch the pattern carefully...</div>
    <button class="btn-game btn-game-primary" id="gm-start">▶ Start</button>
</div>
<div id="grid-memory-board" style="display:none;gap:8px;max-width:380px;margin:0 auto"></div>
<script>
(function(){
    const DIFF={Easy:{size:3,startCells:3,lives:3},Medium:{size:4,startCells:3,lives:3},Hard:{size:5,startCells:4,lives:2}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {size,startCells,lives:maxLives}=DIFF[diff]||DIFF.Medium;
    let level=1,score=0,lives=maxLives,pattern=[],playerInput=[],showing=false,gameActive=false;
    function updateLives(){document.getElementById('gm-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('gm-status');el.textContent=t;el.style.color=c;}
    function start(){
        document.getElementById('gm-start').style.display='none';
        level=1;score=0;lives=maxLives;
        document.getElementById('gm-level').textContent=1;
        document.getElementById('gm-score').textContent=0;
        updateLives(); buildGrid(); setTimeout(nextRound,400);
    }
    function buildGrid(){
        const board=document.getElementById('grid-memory-board');
        board.style.cssText=`display:grid;grid-template-columns:repeat(${size},1fr);gap:8px;max-width:${size*72}px;margin:0 auto`;
        board.innerHTML='';
        for(let i=0;i<size*size;i++){
            const cell=document.createElement('div');
            cell.className='grid-cell';cell.dataset.idx=i;
            cell.addEventListener('click',()=>playerClick(i,cell));
            board.appendChild(cell);
        }
    }
    function nextRound(){
        const count=Math.min(startCells+level-1,size*size-2);
        pattern=[];
        while(pattern.length<count){const r=Math.floor(Math.random()*size*size);if(!pattern.includes(r))pattern.push(r);}
        playerInput=[];showing=true;gameActive=false;
        setStatus('👀 Memorize the pattern!','var(--accent-cyan)');
        showPattern();
    }
    function showPattern(){
        const cells=document.querySelectorAll('.grid-cell');
        let i=0;
        const spd=Math.max(350,700-level*30);
        const interval=setInterval(()=>{
            if(i>0){cells[pattern[i-1]].classList.remove('lit');}
            if(i<pattern.length){cells[pattern[i]].classList.add('lit');i++;}
            else{clearInterval(interval);setTimeout(()=>{cells.forEach(c=>c.classList.remove('lit'));showing=false;gameActive=true;setStatus('🎯 Reproduce the pattern!','var(--accent-orange)');},500);}
        },spd);
    }
    function playerClick(idx,cell){
        if(!gameActive||showing)return;
        const expected=pattern[playerInput.length];
        if(idx===expected){
            cell.classList.add('correct');playerInput.push(idx);
            setTimeout(()=>cell.classList.remove('correct'),350);
            if(playerInput.length===pattern.length){
                gameActive=false;score+=level*10;
                document.getElementById('gm-score').textContent=score;level++;
                document.getElementById('gm-level').textContent=level;
                setStatus('✅ Correct! Next level…','var(--accent-green)');
                setTimeout(nextRound,1000);
            }
        } else {
            gameActive=false;cell.classList.add('wrong');
            pattern.forEach(p=>document.querySelectorAll('.grid-cell')[p].classList.add('lit'));
            lives--;updateLives();
            if(lives<=0){
                setStatus(`❌ Game Over! Final score: ${score}`,'var(--accent-pink)');
                if(typeof submitScore==='function')submitScore(score);
                setTimeout(()=>{document.querySelectorAll('.grid-cell').forEach(c=>c.classList.remove('wrong','lit','correct'));document.getElementById('gm-start').textContent='Play Again';document.getElementById('gm-start').style.display='inline-block';level=1;score=0;lives=maxLives;document.getElementById('gm-level').textContent=1;document.getElementById('gm-score').textContent=0;updateLives();setStatus('Watch the pattern carefully…');},2000);
            } else {
                setStatus(`❌ Wrong! ${lives} lives left`,'var(--accent-pink)');
                setTimeout(()=>{document.querySelectorAll('.grid-cell').forEach(c=>c.classList.remove('wrong','lit','correct'));nextRound();},1500);
            }
        }
    }
    document.getElementById('gm-start').addEventListener('click',start);
})();
</script>
