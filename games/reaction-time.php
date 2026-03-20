<div class="text-center">
    <div class="score-bar justify-content-center mb-4">
        <div class="score-box"><span class="score-num" id="rt-best">---</span><span class="score-label">Best (ms)</span></div>
        <div class="score-box"><span class="score-num" id="rt-last">---</span><span class="score-label">Last (ms)</span></div>
        <div class="score-box"><span class="score-num" id="rt-avg">---</span><span class="score-label">Avg (ms)</span></div>
        <div class="score-box"><span class="score-num" id="rt-tries">0/5</span><span class="score-label">Tries</span></div>
    </div>
    <div id="reaction-target" class="reaction-wait" onclick="handleClick()"><span id="rt-text">Click to Start</span></div>
    <p style="color:var(--text-muted);font-size:14px;margin-top:16px">Click ONLY when the circle turns <span style="color:var(--accent-green);font-weight:700">GREEN</span>!</p>
    <div id="rt-results" class="mt-4" style="display:none">
        <p style="color:var(--accent-green);font-family:var(--font-display);font-size:18px;font-weight:700"><span id="rt-rating"></span></p>
        <p style="color:var(--text-muted);font-size:14px">Score: <strong id="rt-score-val" style="color:var(--primary)"></strong></p>
    </div>
</div>
<script>
(function(){
    const TRIES={Easy:3,Medium:5,Hard:7};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const maxTries=TRIES[diff]||5;
    let state='idle',startTime=0,timeout=null,times=[],tries=0;
    window.handleClick=function(){
        const target=document.getElementById('reaction-target');
        if(state==='idle'){
            state='waiting';target.className='reaction-target reaction-ready';
            document.getElementById('rt-text').textContent='Get Ready...';
            timeout=setTimeout(()=>{state='go';startTime=performance.now();target.className='reaction-target reaction-go';document.getElementById('rt-text').textContent='CLICK!';},1500+Math.random()*(diff==='Hard'?1500:3000));
        } else if(state==='waiting'){
            clearTimeout(timeout);state='idle';target.className='reaction-target reaction-wait';
            document.getElementById('rt-text').textContent='Too early! Try again';
            setTimeout(()=>document.getElementById('rt-text').textContent='Click to Start',1500);
        } else if(state==='go'){
            const ms=Math.round(performance.now()-startTime);
            times.push(ms);tries++;
            document.getElementById('rt-tries').textContent=tries+'/'+maxTries;
            document.getElementById('rt-last').textContent=ms;
            const best=Math.min(...times);document.getElementById('rt-best').textContent=best;
            const avg=Math.round(times.reduce((a,b)=>a+b,0)/times.length);document.getElementById('rt-avg').textContent=avg;
            state='idle';target.className='reaction-target reaction-wait';
            document.getElementById('rt-text').textContent=ms+'ms — Click again!';
            if(tries>=maxTries){
                const rating=avg<180?'⚡ Superhuman':avg<250?'🏆 Excellent':avg<350?'✅ Good':avg<450?'👍 Average':'🐌 Keep Practicing';
                const score=Math.max(0,Math.round(5000-(avg*10)));
                document.getElementById('rt-rating').textContent=rating;
                document.getElementById('rt-score-val').textContent=score;
                document.getElementById('rt-results').style.display='block';
                document.getElementById('rt-text').textContent='Done! Click to play again';
                if(typeof submitScore==='function')submitScore(score);
                tries=0;times=[];
            }
        }
    };
})();
</script>
<style>
#reaction-target{width:220px;height:220px;border-radius:50%;margin:20px auto;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:16px;font-weight:700;cursor:pointer;user-select:none;transition:all 0.15s ease;}
.reaction-wait{background:linear-gradient(135deg,#660000,#cc0033);color:white;box-shadow:0 0 30px rgba(255,0,80,0.4);border:3px solid rgba(255,0,80,0.4);}
.reaction-ready{background:linear-gradient(135deg,#cc7700,#f7931e);color:#0a0a0a;box-shadow:0 0 30px rgba(247,147,30,0.5);border:3px solid rgba(247,147,30,0.4);animation:pulse-ready 0.5s ease infinite;}
.reaction-go{background:linear-gradient(135deg,#226600,#38b000);color:white;box-shadow:0 0 60px rgba(56,176,0,0.7);border:3px solid rgba(56,176,0,0.5);}
@keyframes pulse-ready{0%,100%{transform:scale(1)}50%{transform:scale(1.04)}}
</style>
