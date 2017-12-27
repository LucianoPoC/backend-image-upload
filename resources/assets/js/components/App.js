import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ImageTile from "./ImageTile";
import ReactPaginate from 'react-paginate';

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            uploadTitle: '',
            uploadFile: '',
            items: '',
            pageCount: 9
        };

        this.handleChangeTitle = this.handleChangeTitle.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        let uri = 'http://api.app.local/v1/uploads?limit=9&order_by=id,desc';
        axios.get(uri)
            .then((response) => {
                this.setState({
                    items: response.data.data
                });
            })
            .catch((error) => {
                console.log(error);
            });
    }

    render() {
        return (
            <div className="container">
                <div className="row">
                    <div className="col-md-8 col-md-offset-2">
                        <div className="panel panel-default">
                            <div className="panel-heading">Image Gallery</div>
                            <form onSubmit={this.handleSubmit} encType="multipart/form-data">
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <label>Title:</label>
                                            <input type="text" className="form-control" onChange={this.handleChangeTitle} />
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <label>File:</label>
                                            <input id="file" type="file" className="form-control col-md-6" onChange={this.handleChangeFile} />
                                        </div>
                                    </div>
                                </div><br />
                                <div className="form-group">
                                    <button className="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr />
                { this.renderItems() }

                <ReactPaginate previousLabel={"previous"}
                               nextLabel={"next"}
                               breakLabel={<a href="">...</a>}
                               breakClassName={"break-me"}
                               pageCount={this.state.pageCount}
                               marginPagesDisplayed={2}
                               pageRangeDisplayed={3}
                               onPageChange={this.handlePageClick}
                               containerClassName={"pagination"}
                               subContainerClassName={"pages pagination"}
                               activeClassName={"active"} />
            </div>
        );
    }

    handlePageClick() {

    }

    renderItems() {
        if (this.state.items instanceof Array){
            return this.state.items.map(function(object, i) {
                return  <div className="col-md-4">
                            <ImageTile
                                key={i}
                                title={object.title}
                                image={object.link}
                                downloads={object.downloads}
                                views={object.views}
                            />
                        </div>
            })
        }
    }

    handleChangeTitle(e) {
        this.setState({
            uploadTitle: e.target.value
        })
    }

    handleSubmit(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('title', this.state.uploadTitle);
        data.append('file', document.getElementById('file').files[0]);

        const config = {
            headers: { 'content-type': 'multipart/form-data' }
        };
        const uri = 'http://api.app.local/v1/uploads';



        axios.post(uri, data, config)
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.error(error);
            });
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
