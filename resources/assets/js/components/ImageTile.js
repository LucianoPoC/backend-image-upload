import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

export default class ImageTile extends PureComponent {
    constructor(props) {
        super(props);
        this.showImage = this.showImage.bind(this);
    }

    render() {
        return (
            <div className="card">
                <h4 className="card-title">{this.props.title}</h4>
                <img
                    id={this.props.id}
                    src={this.props.link}
                    alt={this.props.title}
                    className="card-image"
                    onClick={() => {
                        this.showImage(this.props.id);
                        this.props.onViewHandler(this.props.id);
                    }}
                />
                <div className="card-body">
                    <div className="card-option">
                        <a
                            onClick={() => {
                                this.props.onDownloadHandler(this.props.id);
                            }}
                            download
                            href={this.props.link}
                        ><i className="fa fa-download" />Download</a>
                        <span>Downloads: {this.props.downloads}</span>
                    </div>
                    <div className="card-option">
                        <a
                            href="javascript:void(0);"
                            className="view-image-anchor"
                            onClick={() => {
                                this.showImage(this.props.id);
                                this.props.onViewHandler(this.props.id);
                            }}
                        ><i className="fa fa-eye" />Show image</a>
                        <span>Views: {this.props.views}</span>
                    </div>
                </div>
                <div>
                    <div id="myModal" className="modal">
                        <span className="close">&times;</span>
                        <img className="modal-content" id="img01" />
                        <div id="caption" />
                    </div>
                </div>
            </div>
        );
    }

    showImage(id) {
        // Get the modal
        let modal = document.getElementById('myModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        let image = document.getElementById(id);
        let modalImage = document.getElementById("img01");
        let captionText = document.getElementById("caption");
        modal.style.display = "block";
        modalImage.src = image.src;
        captionText.innerHTML = image.alt;

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = () => {
            modal.style.display = "none";
        }
    }
}

ImageTile.propTypes = {
    title: PropTypes.string,
    id: PropTypes.number.isRequired,
    link: PropTypes.string.isRequired,
    downloads: PropTypes.number.isRequired,
    views: PropTypes.number.isRequired,
    onViewHandler: PropTypes.func.isRequired,
    onDownloadHandler: PropTypes.func.isRequired
};