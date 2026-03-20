<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="fm-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="fm-round">1</span><span class="score-label">Round</span></div>
        <div class="score-box"><span class="score-num" id="fm-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="fm-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:16px">Memorize the faces and their positions!</div>
    <button class="btn-game btn-game-primary" id="fm-start">▶ Start</button>
</div>
<div id="fm-board" style="display:none;max-width:380px;margin:0 auto"></div>
<div id="fm-question" class="text-center" style="display:none"></div>
<script>
(function(){
    const DIFF={Easy:{count:4,showTime:3000},Medium:{count:6,showTime:4000},Hard:{count:9,showTime:5000}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {count,showTime}=DIFF[diff]||DIFF.Medium;
    const faces=['😀','😎','🥳','🤩','😍','🤪','😜','🤓','😏','🤯','😇','🧐','🥸','😤','🤗'];
    let round=1,score=0,lives=3,grid=[],answer=-1,phase='memorize';
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('fm-status');el.textContent=t;el.style.color=c;}
    function updateLives(){document.getElementById('fm-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function start(){document.getElementById('fm-start').style.display='none';round=1;score=0;lives=3;document.getElementById('fm-round').textContent=1;document.getElementById('fm-score').textContent=0;updateLives();nextRound();}
    function nextRound(){
        const sz=Math.ceil(Math.sqrt(count));
        const board=document.getElementById('fm-board');
        board.style.cssText=`display:grid;grid-template-columns:repeat(${sz},1fr);gap:10px`;
        board.style.display='grid';
        document.getElementById('fm-question').style.display='none';
        const picked=[...faces].sort(()=>Math.random()-.5).slice(0,count);
        grid=picked;
        picked.forEach(face=>{
            const c=document.createElement('div');
            c.style.cssText='aspect-ratio:1;border-radius:12px;background:var(--bg-card2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:42px';
            c.textContent=face;board.appendChild(c);
        });
        phase='memorize';setStatus('👀 Memorize the faces!','var(--accent-cyan)');
        setTimeout(askQuestion,showTime);
    }
    function askQuestion(){
        phase='answer';
        const board=document.getElementById('fm-board');
        const qi=Math.floor(Math.random()*grid.length);answer=qi;
        const faceToFind=grid[qi];
        board.innerHTML='';
        grid.forEach((_,i)=>{
            const c=document.createElement('div');
            c.style.cssText='aspect-ratio:1;border-radius:12px;background:var(--bg-card2);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;cursor:pointer;transition:all 0.2s;color:var(--text-muted)';
            c.textContent='?';
            c.onmouseenter=()=>{c.style.borderColor='var(--primary)';c.style.background='rgba(108,99,255,0.1)';};
            c.onmouseleave=()=>{c.style.borderColor='var(--border)';c.style.background='var(--bg-card2)';};
            c.onclick=()=>checkAnswer(i,c);
            board.appendChild(c);
        });
        document.getElementById('fm-question').style.display='block';
        document.getElementById('fm-question').innerHTML=`<p style="font-size:52px;margin-bottom:8px">${faceToFind}</p><p style="color:var(--text-muted)">Where was this face?</p>`;
        setStatus('Click where this face was!','var(--accent-orange)');
    }
    function checkAnswer(i,cell){
        if(phase!=='answer')return;phase='result';
        const cells=document.querySelectorAll('#fm-board div');
        cells[answer].style.background='rgba(56,176,0,0.2)';cells[answer].style.borderColor='var(--accent-green)';cells[answer].textContent=grid[answer];
        if(i===answer){
            score+=round*10;document.getElementById('fm-score').textContent=score;
            setStatus('✅ Correct!','var(--accent-green)');round++;document.getElementById('fm-round').textContent=round;
            setTimeout(()=>{document.getElementById('fm-board').innerHTML='';nextRound();},1200);
        } else {
            cell.style.background='rgba(255,0,110,0.2)';cell.style.borderColor='var(--accent-pink)';cell.textContent=grid[i];
            lives--;updateLives();
            if(lives<=0){
                setStatus(`❌ Game Over! Score: ${score}`,'var(--accent-pink)');
                if(typeof submitScore==='function')submitScore(score);
                setTimeout(()=>{document.getElementById('fm-board').innerHTML='';document.getElementById('fm-question').style.display='none';document.getElementById('fm-start').textContent='Play Again';document.getElementById('fm-start').style.display='inline-block';round=1;score=0;lives=3;document.getElementById('fm-round').textContent=1;document.getElementById('fm-score').textContent=0;updateLives();setStatus('Memorize the faces and their positions!');},2000);
            } else {
                setStatus(`❌ Wrong! ${lives} lives left`,'var(--accent-pink)');
                setTimeout(()=>{document.getElementById('fm-board').innerHTML='';nextRound();},1500);
            }
        }
    }
    document.getElementById('fm-start').addEventListener('click',start);
})();
</script>
