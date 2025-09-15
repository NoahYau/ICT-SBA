<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Player</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .audio-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .audio-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .audio-item:hover {
            transform: translateY(-5px);
        }
        .audio-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #3498db;
        }
        .audio-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .status {
            text-align: center;
            margin-top: 15px;
            font-style: italic;
            color: #7f8c8d;
        }
        .instructions {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1>Audio Player</h1>
    
    <div class="instructions">
        <p>Click any play button to play that audio. All other audio will automatically pause.</p>
    </div>
    
    <div class="audio-container">
        <div class="audio-item">
            <div class="audio-title">Bright Sound</div>
            <div class="audio-controls">
                <button id="audio_bright">Play Bright</button>
            </div>
            <div class="status" id="status_bright">Not playing</div>
            <audio id="bright" class="audio">
                <source src="./audio/bright.mp3" type="audio/mpeg">
            </audio>
        </div>
        
        <div class="audio-item">
            <div class="audio-title">Neutral Sound</div>
            <div class="audio-controls">
                <button id="audio_neutral">Play Neutral</button>
            </div>
            <div class="status" id="status_neutral">Not playing</div>
            <audio id="neutral" class="audio">
                <source src="./audio/neutral.mp3" type="audio/mpeg">
            </audio>
        </div>
        
        <div class="audio-item">
            <div class="audio-title">Bass Sound</div>
            <div class="audio-controls">
                <button id="audio_bass">Play Bass</button>
            </div>
            <div class="status" id="status_bass">Not playing</div>
            <audio id="bass" class="audio">
                <source src="https://assets.mixkit.co/music/preview/mixkit-hip-hop-02-621.mp3" type="audio/mpeg">
            </audio>
        </div>
        
        <div class="audio-item">
            <div class="audio-title">Spacious Sound</div>
            <div class="audio-controls">
                <button id="audio_spacious">Play Spacious</button>
            </div>
            <div class="status" id="status_spacious">Not playing</div>
            <audio id="spacious" class="audio">
                <source src="https://assets.mixkit.co/music/preview/mixkit-spooky-ambience-529.mp3" type="audio/mpeg">
            </audio>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all audio elements with class 'audio'
            const audio_pause = document.querySelectorAll('.audio');
            
            // Bright audio
            const bright = document.getElementById('bright');
            const audioTrigger_bright = document.getElementById('audio_bright');
            const status_bright = document.getElementById('status_bright');

            audioTrigger_bright.onclick = function() {
                // Pause all audio elements
                audio_pause.forEach(audio => {
                    audio.pause();
                    // Update status for all audio elements
                    document.getElementById(`status_${audio.id}`).textContent = "Not playing";
                });
                
                // Play the bright audio
                bright.play();
                status_bright.textContent = "Playing...";
            };
            
            // Neutral audio
            const neutral = document.getElementById('neutral');
            const audioTrigger_neutral = document.getElementById('audio_neutral');
            const status_neutral = document.getElementById('status_neutral');

            audioTrigger_neutral.onclick = function() {
                // Pause all audio elements
                audio_pause.forEach(audio => {
                    audio.pause();
                    // Update status for all audio elements
                    document.getElementById(`status_${audio.id}`).textContent = "Not playing";
                });
                
                // Play the neutral audio
                neutral.play();
                status_neutral.textContent = "Playing...";
            };
            
            // Bass audio
            const bass = document.getElementById('bass');
            const audioTrigger_bass = document.getElementById('audio_bass');
            const status_bass = document.getElementById('status_bass');

            audioTrigger_bass.onclick = function() {
                // Pause all audio elements
                audio_pause.forEach(audio => {
                    audio.pause();
                    // Update status for all audio elements
                    document.getElementById(`status_${audio.id}`).textContent = "Not playing";
                });
                
                // Play the bass audio
                bass.play();
                status_bass.textContent = "Playing...";
            };
            
            // Spacious audio
            const spacious = document.getElementById('spacious');
            const audioTrigger_spacious = document.getElementById('audio_spacious');
            const status_spacious = document.getElementById('status_spacious');

            audioTrigger_spacious.onclick = function() {
                // Pause all audio elements
                audio_pause.forEach(audio => {
                    audio.pause();
                    // Update status for all audio elements
                    document.getElementById(`status_${audio.id}`).textContent = "Not playing";
                });
                
                // Play the spacious audio
                spacious.play();
                status_spacious.textContent = "Playing...";
            };
            
            // Add event listeners for when audio ends
            audio_pause.forEach(audio => {
                audio.addEventListener('ended', function() {
                    document.getElementById(`status_${this.id}`).textContent = "Finished playing";
                });
            });
        });
    </script>
</body>
</html>