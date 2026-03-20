<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="ms-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="ms-timer">60</span><span class="score-label">Time</span></div>
        <div class="score-box"><span class="score-num" id="ms-streak">0</span><span class="score-label">Streak</span></div>
        <div class="score-box"><span class="score-num" id="ms-combo">x1</span><span class="score-label">Combo</span></div>
    </div>
    <button class="btn-game btn-game-primary" id="ms-start">▶ Start Sprint</button>
</div>
<div id="ms-game" style="display:none">
    <div class="progress-bar-custom mb-3"><div class="progress-fill" id="ms-progress" style="width:100%"></div></div>
    <div class="math-equation" id="ms-equation">?</div>
    <div class="math-options" id="ms-options"></div>
</div>
<div id="ms-over" class="text-center" style="display:none">
    <div style="font-size:60px">🧮</div>
    <h3 class="section-title">Sprint Complete!</h3>
    <div class="score-bar justify-content-center mt-3 mb-3">
        <div class="score-box"><span class="score-num" id="ms-final-score" style="color:var(--accent-green)"></span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="ms-final-correct"></span><span class="score-label">Correct</span></div>
        <div class="score-box"><span class="score-num" id="ms-final-streak"></span><span class="score-label">Best Streak</span></div>
    </div>
    <button class="btn-game btn-game-primary" id="ms-restart">Sprint Again</button>
</div>
<script>
(function(){
    const DIFF_CFG={Easy:{time:90,ops:['+']},Medium:{time:60,ops:['+','-']},Hard:{time:45,ops:['+','-','×']}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {time:totalTime,ops}=DIFF_CFG[diff]||DIFF_CFG.Medium;
    let score=0,streak=0,bestStreak=0,combo=1,timeLeft=totalTime,timer,correct=0,active=false,answered=false,curAnswer=0;
    function genQ(level){
        const op=ops[Math.floor(Math.random()*ops.length)];
        let a,b,ans;
        if(op==='+'){a=Math.floor(Math.random()*(10*Math.ceil(level/2)))+1;b=Math.floor(Math.random()*(10*Math.ceil(level/2)))+1;ans=a+b;}
        else if(op==='-'){a=Math.floor(Math.random()*50)+10;b=Math.floor(Math.random()*a)+1;ans=a-b;}
        else{a=Math.floor(Math.random()*9)+2;b=Math.floor(Math.random()*9)+2;ans=a*b;}
        return{text:`${a} ${op} ${b} = ?`,ans};
    }
    function start(){
        document.getElementById('ms-start').style.display='none';document.getElementById('ms-over').style.display='none';document.getElementById('ms-game').style.display='block';
        score=0;streak=0;bestStreak=0;combo=1;timeLeft=totalTime;correct=0;active=true;answered=false;
        document.getElementById('ms-timer').textContent=totalTime;document.getElementById('ms-score').textContent=0;document.getElementById('ms-streak').textContent=0;document.getElementById('ms-combo').textContent='x1';
        clearInterval(timer);
        timer=setInterval(()=>{timeLeft--;document.getElementById('ms-timer').textContent=timeLeft;document.getElementById('ms-progress').style.width=(timeLeft/totalTime*100)+'%';if(timeLeft<=0){clearInterval(timer);endGame();}},1000);
        nextQuestion();
    }
    function nextQuestion(){
        if(!active)return;answered=false;
        const level=Math.floor(correct/5)+1;const q=genQ(level);curAnswer=q.ans;
        document.getElementById('ms-equation').textContent=q.text;
        const opts=new Set([curAnswer]);
        while(opts.size<4){const v=curAnswer+Math.floor(Math.random()*11)-5;if(v!==curAnswer&&v>=0)opts.add(v);}
        const shuffled=[...opts].sort(()=>Math.random()-.5);
        const container=document.getElementById('ms-options');container.innerHTML='';
        shuffled.forEach(opt=>{const btn=document.createElement('button');btn.className='math-option';btn.textContent=opt;btn.addEventListener('click',()=>handleAnswer(opt,btn));container.appendChild(btn);});
    }
    function handleAnswer(val,btn){
        if(!active||answered)return;answered=true;
        if(val===curAnswer){btn.classList.add('correct');streak++;correct++;if(streak>bestStreak)bestStreak=streak;if(streak>=3)combo=Math.min(4,Math.floor(streak/3)+1);score+=10*combo;}
        else{btn.classList.add('wrong');document.querySelectorAll('.math-option').forEach(b=>{if(parseInt(b.textContent)===curAnswer)b.classList.add('correct');});streak=0;combo=1;}
        document.getElementById('ms-score').textContent=score;document.getElementById('ms-streak').textContent=streak;document.getElementById('ms-combo').textContent='x'+combo;
        setTimeout(nextQuestion,400);
    }
    function endGame(){
        active=false;document.getElementById('ms-game').style.display='none';
        document.getElementById('ms-final-score').textContent=score;document.getElementById('ms-final-correct').textContent=correct;document.getElementById('ms-final-streak').textContent=bestStreak;
        document.getElementById('ms-over').style.display='block';
        if(typeof submitScore==='function')submitScore(score);
    }
    document.getElementById('ms-start').addEventListener('click',start);
    document.getElementById('ms-restart').addEventListener('click',start);
})();
</script>
