let linksVideos = document.querySelectorAll('[data-delete-video]');

for(let link of linksVideos) {
    link.addEventListener('click', function(e){
        e.preventDefault();
        if(confirm('Voulez-vous supprimer cette vidéo ?')){
            fetch(this.getAttribute('href'), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(
                response => response.json()
            ).then(data => {
                if(data.success)
                    this.parentElement.remove();
                else
                    alert('Une erreur est survenue');
            }).catch(e => alert(e));
        }
    });
}
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('addVideoButton').addEventListener('click', function() {
        // Créez un nouveau champ "videos"
        var newVideoField = document.createElement('div');
        newVideoField.innerHTML = '<div class="mt-3 video-field"><label>Ajouter une vidéo embed de la plateforme de votre choix (Youtube, Dailymotion, Vimeo, etc...)</label><input type="text" id="trick_form_videos_iframe" name="trick_form[videos][]" class="form-control"></div>';

        // Ajoutez le nouveau champ à la collection
        document.getElementById('videosContainer').appendChild(newVideoField);
    });
    $(".remove-video").on('click', function() {
        console.log('test');
        const videoField = $(this).closest('.video-field');
        videoField.remove()
    });
});