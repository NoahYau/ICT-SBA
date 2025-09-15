<?php
    session_start();
    $connectdb = mysqli_connect("localhost","root","","dogebee");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
<!DOCTYPE html>
<html lang="zh_Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Perfect Earphones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" href="photos/doge_logo_v2.png" type="image/png" />
    <link rel="stylesheet" href="./css&js/index_ai.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        /* ===== Inherited Styles from sound_preview.php ===== */
        body {
            background: linear-gradient(160deg, #a4c0ee, #cbc1ec, #f9c4eb);
        }
        .sound-preview-container {
            min-height: calc(100vh - 182px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        .preview-content {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
        }
        .preview-header {
            margin-bottom: 30px;
        }
        .preview-header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .preview-header p {
            color: #7f8c8d;
            font-size: 18px;
            line-height: 1.6;
        }
        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Default to 2 columns */
            gap: 20px;
            margin-bottom: 40px;
        }
        .option-card {
            background: white;
            border-radius: 15px;
            padding: 25px 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-color: #3498db;
        }
        .option-card.selected {
            background: #e3f2fd;
            border-color: #3498db;
        }
        .option-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #3498db;
        }
        .option-title {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 500;
        }
        .option-desc {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 8px;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        .progress-bar-container {
            height: 10px;
            flex-grow: 1;
            background: #ecf0f1;
            border-radius: 10px;
            margin: 0 20px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: #3498db;
            border-radius: 10px;
            width: 0%;
            transition: width 0.5s ease;
        }
        .nav-btn {
            background: #3498db;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-btn.back {
            background: #7f8c8d;
            box-shadow: 0 5px 15px rgba(127, 140, 141, 0.4);
        }
        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.6);
        }
        .nav-btn.back:hover {
            background: #636e70;
            box-shadow: 0 8px 20px rgba(127, 140, 141, 0.6);
        }
        .nav-btn i { margin-left: 8px; }
        .nav-btn i.fa-arrow-left { margin-right: 8px; margin-left: 0; }

        /* Styles for functionality */
        .entry { display: none; }
        .entry:first-child { display: block; }
        .result-card { text-align: left; }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="sound-preview-container">
        <div class="preview-content">

            <div class="entry">
                <div class="preview-header">
                    <h1>Find Your Perfect Earphones</h1>
                    <p>Answer a few questions to discover earphones that match your preferences.</p>
                </div>
                <div class="options-grid" style="grid-template-columns: 1fr;">
                    <div class="option-card" data-value="start">
                        <div class="option-title">Let's Get Started</div>
                    </div>
                </div>
            </div>
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const audio_pause = document.querySelectorAll('.audio');
    //     const bright = document.getElementById('bright');
    //     const audioTrigger_bright = document.getElementById('audio_bright');

    //     audioTrigger_bright.onclick = function() {
    //         audio_pause.pause();
    //         bright.play();
    //     };
    //     const neutral = document.getElementById('neutral');
    //     const audioTrigger_neutral = document.getElementById('audio_neutral');

    //     audioTrigger_neutral.onclick = function() {
    //         neutral.play();
    //     };
    //     const bass = document.getElementById('bass');
    //     const audioTrigger_bass = document.getElementById('audio_bass');

    //     audioTrigger_bass.onclick = function() {
    //         bass.play();
    //     };
    //     const spacious = document.getElementById('spacious');
    //     const audioTrigger_spacious = document.getElementById('audio_spacious');

    //     audioTrigger_spacious.onclick = function() {
    //         audio_pause.pause();
    //         spacious.play();
    //     };
        
    // });

</script>
<audio id="bright" src="./audio/bright.mp3"></audio>
<audio id="neutral" src="./audio/neutral.mp3"></audio>
<audio id="bass" src="./audio/bass.mp3"></audio>
<audio id="spacious" src="./audio/crab_wave.mp3"></audio>

            <div class="entry">
                <div class="preview-header">
                    <h1>Feel the sound difference, choose the most suitable sound.</h1>
                    <p>Please choose your favourate sound according to what you have listened.</p>
                </div>
                <div class="options-grid">
                    <div class="option-card audio" id="audio_bright">
                        <div class="option-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="option-title">Clear and Bright</div>
                        <div class="option-desc">Good Treble</div>
                    </div>

                    <div class="option-card audio" id="audio_?">
                        <div class="option-icon">
                            <i class="fas fa-wave-square"></i>
                        </div>
                        <div class="option-title">Warm and Thick</div>
                        <div class="option-desc">Good in Middle and Bass</div>
                    </div>

                    <div class="option-card audio" id="audio_neutral">
                        <div class="option-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="option-title">Neutral</div>
                        <div class="option-desc">Presenting the true sound</div>
                    </div>
                    
                    <div class="option-card audio" id="audio_bass">
                        <div class="option-icon">
                            <i class="fas fa-volume-up"></i>
                        </div>
                        <div class="option-title">Bass</div>
                        <div class="option-desc">Great Dynamics</div>
                    </div>
                    
                    <div class="option-card audio" id="audio_spacious">
                        <div class="option-icon">
                            <i class="fas fa-expand-alt"></i>
                        </div>
                        <div class="option-title">Spacious</div>
                        <div class="option-desc">Spactial Audio</div>
                    </div>
                </div>
            </div>

            <div class="entry">
                <div class="preview-header">
                    <h1>Which sound signature do you prefer?</h1>
                </div>
                <div class="options-grid">
                    <div class="option-card" data-value="bass"><div class="option-title">Strong bass</div></div>
                    <div class="option-card" data-value="balanced"><div class="option-title">Balanced sound</div></div>
                    <div class="option-card" data-value="treble"><div class="option-title">Bright treble</div></div>
                    <div class="option-card" data-value="v-shaped"><div class="option-title">V-shaped (emphasized bass and treble)</div></div>
                </div>
            </div>

            <div class="entry">
                <div class="preview-header">
                    <h1>What's your preferred earphone type?</h1>
                </div>
                <div class="options-grid">
                    <div class="option-card" data-value="in-ear"><div class="option-title">In-ear</div></div>
                    <div class="option-card" data-value="earbuds"><div class="option-title">Earbuds</div></div>
                    <div class="option-card" data-value="over-ear"><div class="option-title">Over-ear</div></div>
                    <div class="option-card" data-value="true-wireless"><div class="option-title">True wireless</div></div>
                </div>
            </div>
            
            <div class="entry">
                <div class="preview-header">
                     <h1>What's your budget range?</h1>
                </div>
                <div class="options-grid">
                    <div class="option-card" data-value="budget"><div class="option-title">Under $500</div></div>
                    <div class="option-card" data-value="mid"><div class="option-title">$500 - $1500</div></div>
                    <div class="option-card" data-value="premium"><div class="option-title">$1500 - $3000</div></div>
                    <div class="option-card" data-value="high-end"><div class="option-title">$3000+</div></div>
                </div>
            </div>

            <div class="entry">
                <div class="preview-header">
                    <h1>Which feature is most important to you?</h1>
                </div>
                <div class="options-grid">
                    <div class="option-card" data-value="noise-canceling"><div class="option-title">Noise cancellation</div></div>
                    <div class="option-card" data-value="battery"><div class="option-title">Battery life</div></div>
                    <div class="option-card" data-value="comfort"><div class="option-title">Comfort</div></div>
                    <div class="option-card" data-value="sound-quality"><div class="option-title">Sound quality</div></div>
                </div>
            </div>

            <div class="entry">
                <div class="preview-header">
                    <h1>Your Perfect Earphones!</h1>
                    <p>Based on your preferences, we recommend the following:</p>
                </div>
                <div class="option-card result-card">
                    <h2 class="option-title">Audio-Technica ATH-M50x</h2>
                    <p style="margin-top: 10px; color: #555;">These professional monitor headphones provide exceptional clarity with deep, accurate bass response. They're perfect for critical listening and studio work.</p>
                </div>
            </div>
            
            <div class="pagination">
                <button class="nav-btn back_btn"><i class="fas fa-arrow-left"></i> Back</button>
                <div class="progress-bar-container">
                    <div class="progress-bar"></div>
                </div>
                <button class="nav-btn next_btn">Next <i class="fas fa-arrow-right"></i></button>
                <button class="nav-btn" id="restart" style="display: none;">Start Over <i class="fas fa-redo"></i></button>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            const entryElements = $('.entry');
            const nextBtn = $('.next_btn');
            const backBtn = $('.back_btn');
            const restartBtn = $('#restart');
            const progressBar = $('.progress-bar');
            let entryIndex = 0;
            const userSelections = {};

            function updateUI() {
                entryElements.hide();
                $(entryElements[entryIndex]).show();

                // Progress Bar
                const progress = (entryIndex / (entryElements.length - 2)) * 100;
                progressBar.css('width', progress + '%');

                // Button Visibility
                backBtn.toggle(entryIndex > 0 && entryIndex < entryElements.length - 1);
                nextBtn.toggle(entryIndex < entryElements.length - 1);
                restartBtn.toggle(entryIndex === entryElements.length - 1);

                // Update Next Button Text
                if (entryIndex === entryElements.length - 2) {
                    nextBtn.html('See Results <i class="fas fa-arrow-right"></i>');
                } else {
                    nextBtn.html('Next <i class="fas fa-arrow-right"></i>');
                }
            }

            $('.option-card').click(function() {
                $(this).closest('.options-grid').find('.option-card').removeClass('selected');
                $(this).addClass('selected');
                
                const question = $(this).closest('.entry').find('h1').text();
                userSelections[question] = $(this).data('value');
            });

            nextBtn.click(function() {
                const currentEntry = $(entryElements[entryIndex]);
                if (currentEntry.find('.option-card').length > 0 && currentEntry.find('.option-card.selected').length === 0) {
                    alert('Please select an option to continue.');
                    return;
                }
                if (entryIndex < entryElements.length - 1) {
                    entryIndex++;
                    updateUI();
                }
            });

            backBtn.click(function() {
                if (entryIndex > 0) {
                    entryIndex--;
                    updateUI();
                }
            });

            restartBtn.click(function() {
                entryIndex = 0;
                $('.option-card').removeClass('selected');
                for (let key in userSelections) {
                    delete userSelections[key];
                }
                updateUI();
            });

            // Initial UI setup
            updateUI();
        });
    </script>
</body>
</html>