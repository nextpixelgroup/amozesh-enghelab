<template>
    <div>
        <div class="zo-intro-section">
            <div class="zo-player">
                <img src="/assets/img/site/player.svg" alt="">
            </div>
            <div class="zo-thumbnail">
                <img src="/assets/img/sample/video.png" alt="">
            </div>
            <div class="zo-content">
                <h1>دوره کتاب عیار ۲۴</h1>
                <p>
                    این کتاب، مجموعه‌ای است از پندها و نصایح مقام معظّم رهبری به زوج‌های جوانی که توفیق یافته‌اند پیوند
                    زناشویی خود را با آهنگ کلام ایشان هماهنگ کنند و در آغاز زندگی از سرچشمه‌ی فیض و حکمت آن عالِم عارف
                    بهره گیرند.
                </p>
            </div>
        </div>
    </div>
</template>
<script setup>
</script>
<style scoped>
.zo-intro-section {
    position: relative;
}

.zo-intro-section .zo-player {
    margin: 0 auto;
    position: absolute;
    top: 50%;
    right: 0;
    left: 0;
    transform: translateY(-50%);
    text-align: center;
    z-index: 15
}

.zo-intro-section .zo-thumbnail img {
    width: 100%;
    border-radius: .75rem
}

.zo-intro-section .zo-content {
    padding: 0 30px;
    position: absolute;
    right: 0;
    bottom: 30px;
    left: 0;
    color: rgb(255, 255, 255)
}

.zo-intro-section .zo-content h1 {
    display: block;
    margin: 0 0 5px;
    padding: 0 15px 0 0;
    position: relative;
    font-size: 1.5rem
}

.zo-intro-section .zo-content h1:before {
    content: '';
    width: 2.5px;
    height: 75%;
    position: absolute;
    top: 5px;
    right: 0;
    background: rgb(200, 160, 100)
}
</style>
