<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="ds-level">-</span><span class="score-label">Digits</span></div>
        <div class="score-box"><span class="score-num" id="ds-best">0</span><span class="score-label">Best</span></div>
        <div class="score-box"><span class="score-num" id="ds-strikes">⚡⚡⚡</span><span class="score-label">Lives</span></div>
    </div>
    <div id="ds-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Remember the growing digit sequence!</div>
    <button class="btn-game btn-game-primary" id="ds-start">▶ Start</button>
</div>
<div id="ds-display" class="digit-display" style="display:none;min-height:140px;flex-direction:column;gap:8px">
    <div id="ds-seq-wrap" style="display:flex;gap:16px;flex-wrap:wrap;justify-content:center"></div>
</div>
<div id="ds-input-area" style="display:none;text-align:center">
    <p style="color:var(--text-muted);margin-bottom:16px">Type the digits you saw:</p>
    <input type="text" id="ds-input" maxlength="20" style="font-family:var(--font-display);font-size:30px;font-weight:900;letter-spacing:10px;background:var(--bg-card2);border:2px solid var(--border);color:var(--primary);border-radius:12px;padding:16px 24px;width:100%;max-width:420px;text-align:center;outline:none" placeholder="...">
    <br><button class="btn-game btn-game-primary mt-3" id="ds-submit">Submit</button>
</div>
<script>
(function(){
    const DIFF={Easy:{start:3,flashTime:900,lives:3},Medium:{start:4,flashTime:750,lives:3},Hard:{start:5,flashTime:550,lives:2}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {start:startDigits,flashTime,lives:maxLives}=DIFF[diff]||DIFF.Medium;
    let digitCount=startDigits,best=0,strikes=0,sequence=[],phase='idle',score=0;
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('ds-status');el.textContent=t;el.style.color=c;}
    function updateStrikes(){document.getElementById('ds-strikes').textContent='⚡'.repeat(Math.max(0,maxLives-strikes));}
    function start(){document.getElementById('ds-start').style.display='none';digitCount=startDigits;strikes=0;score=0;document.getElementById('ds-level').textContent=digitCount;document.getElementById('ds-best').textContent=best;updateStrikes();newRound();}
    async function newRound(){
        sequence=[];for(let i=0;i<digitCount;i++)sequence.push(Math.floor(Math.random()*10));
        document.getElementById('ds-input-area').style.display='none';document.getElementById('ds-input').value='';document.getElementById('ds-input').style.borderColor='var(--border)';
        const display=document.getElementById('ds-display'),wrap=document.getElementById('ds-seq-wrap');
        display.style.display='flex';wrap.innerHTML='';phase='showing';setStatus('👀 Memorize!','var(--accent-cyan)');
        for(let i=0;i<sequence.length;i++){
            wrap.innerHTML='';const span=document.createElement('span');
            span.style.cssText='font-family:var(--font-display);font-size:72px;font-weight:900;color:var(--primary);text-shadow:0 0 30px var(--primary-glow)';
            span.textContent=sequence[i];wrap.appendChild(span);await delay(flashTime);
        }
        wrap.innerHTML='';display.style.display='none';phase='input';
        document.getElementById('ds-input-area').style.display='block';document.getElementById('ds-input').focus();
        setStatus('🎯 Type what you saw!','var(--accent-orange)');
    }
    document.getElementById('ds-submit').addEventListener('click',evaluate);
    document.getElementById('ds-input').addEventListener('keydown',e=>{if(e.key==='Enter')evaluate();});
    function evaluate(){
        if(phase!=='input')return;
        const val=document.getElementById('ds-input').value.trim();const correct=sequence.join('');
        if(val===correct){
            document.getElementById('ds-input').style.borderColor='var(--accent-green)';
            score+=digitCount*10;if(digitCount>best){best=digitCount;document.getElementById('ds-best').textContent=best;}
            digitCount++;document.getElementById('ds-level').textContent=digitCount;
            setStatus(`✅ Correct! ${digitCount} digits next!`,'var(--accent-green)');setTimeout(newRound,900);
        } else {
            document.getElementById('ds-input').style.borderColor='var(--accent-pink)';
            strikes++;updateStrikes();setStatus(`❌ Wrong! It was: ${correct}`,'var(--accent-pink)');
            if(strikes>=maxLives){
                if(typeof submitScore==='function')submitScore(score);
                setTimeout(()=>{document.getElementById('ds-input-area').style.display='none';document.getElementById('ds-start').textContent='Play Again';document.getElementById('ds-start').style.display='inline-block';digitCount=startDigits;strikes=0;score=0;document.getElementById('ds-level').textContent=startDigits;updateStrikes();setStatus('Remember the growing digit sequence!');},2000);
            } else setTimeout(newRound,1500);
        }
    }
    function delay(ms){return new Promise(r=>setTimeout(r,ms));}
    document.getElementById('ds-start').addEventListener('click',start);
})();
</script>
