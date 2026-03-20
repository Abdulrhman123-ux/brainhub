<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="ns-level">1</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="ns-best">0</span><span class="score-label">Best</span></div>
        <div class="score-box"><span class="score-num" id="ns-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="ns-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Remember the number sequence!</div>
    <button class="btn-game btn-game-primary" id="ns-start">▶ Start</button>
</div>
<div id="ns-display" class="digit-display" style="display:none;min-height:140px"></div>
<div id="ns-input-area" style="display:none;text-align:center">
    <p style="color:var(--text-muted);margin-bottom:12px;font-size:14px">Type the numbers in order:</p>
    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:16px" id="ns-input-boxes"></div>
    <button class="btn-game btn-game-primary" id="ns-submit">Submit</button>
</div>
<script>
(function(){
    const DIFF={Easy:{start:3,flashTime:900},Medium:{start:4,flashTime:750},Hard:{start:5,flashTime:550}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {start:startLen,flashTime}=DIFF[diff]||DIFF.Medium;
    let level=1,best=0,lives=3,sequence=[],score=0;
    function updateLives(){document.getElementById('ns-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('ns-status');el.textContent=t;el.style.color=c;}
    function start(){document.getElementById('ns-start').style.display='none';level=1;best=0;lives=3;score=0;document.getElementById('ns-level').textContent=1;document.getElementById('ns-best').textContent=0;updateLives();nextRound();}
    async function nextRound(){
        const count=startLen+level-1;sequence=[];
        for(let i=0;i<count;i++)sequence.push(Math.floor(Math.random()*9)+1);
        document.getElementById('ns-input-area').style.display='none';
        const display=document.getElementById('ns-display');display.style.display='flex';display.textContent='';
        setStatus('👀 Memorize!','var(--accent-cyan)');
        for(let i=0;i<sequence.length;i++){display.textContent='';await delay(150);display.textContent=sequence[i];await delay(flashTime);}
        display.textContent='?';await delay(200);display.style.display='none';
        showInput(count);
    }
    function showInput(count){
        const boxes=document.getElementById('ns-input-boxes');boxes.innerHTML='';
        for(let i=0;i<count;i++){
            const inp=document.createElement('input');inp.type='number';inp.min=1;inp.max=9;inp.maxLength=1;
            inp.style.cssText='width:52px;height:52px;border-radius:10px;background:var(--bg-card2);border:2px solid var(--border);color:var(--text-main);font-family:var(--font-display);font-size:22px;font-weight:700;text-align:center;outline:none;';
            inp.oninput=()=>{if(inp.value.length>=1&&i<count-1)boxes.children[i+1].focus();};
            inp.onfocus=()=>inp.style.borderColor='var(--primary)';inp.onblur=()=>inp.style.borderColor='var(--border)';
            boxes.appendChild(inp);
        }
        if(boxes.children[0])boxes.children[0].focus();
        document.getElementById('ns-input-area').style.display='block';
        setStatus('🎯 Enter the sequence!','var(--accent-orange)');
    }
    document.getElementById('ns-submit').addEventListener('click',()=>{
        const boxes=document.getElementById('ns-input-boxes');
        const inputs=[...boxes.querySelectorAll('input')].map(i=>parseInt(i.value)||0);
        let correct=true;
        inputs.forEach((val,i)=>{const box=boxes.children[i];if(val===sequence[i]){box.style.borderColor='var(--accent-green)';box.style.color='var(--accent-green)';}else{box.style.borderColor='var(--accent-pink)';box.style.color='var(--accent-pink)';correct=false;}});
        if(correct){
            score+=level*10;if(level>best){best=level;document.getElementById('ns-best').textContent=best;}
            level++;document.getElementById('ns-level').textContent=level;
            setStatus('✅ Perfect!','var(--accent-green)');setTimeout(nextRound,900);
        } else {
            lives--;updateLives();setStatus(`❌ It was: ${sequence.join(' ')} | ${lives} lives left`,'var(--accent-pink)');
            if(lives<=0){
                if(typeof submitScore==='function')submitScore(score);
                setTimeout(()=>{document.getElementById('ns-input-area').style.display='none';document.getElementById('ns-start').textContent='Try Again';document.getElementById('ns-start').style.display='inline-block';level=1;lives=3;score=0;document.getElementById('ns-level').textContent=1;updateLives();setStatus('Remember the number sequence!');},2500);
            } else {setTimeout(nextRound,2000);}
        }
    });
    function delay(ms){return new Promise(r=>setTimeout(r,ms));}
    document.getElementById('ns-start').addEventListener('click',start);
})();
</script>
